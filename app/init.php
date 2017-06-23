<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Pagination\Paginator;
use Geggleto\Service\Captcha;
use ReCaptcha\ReCaptcha;

// Start PHP session
session_start();

// include user configuration
$config = require __DIR__ .  '/../config.inc.php';

// autoload composer libs
require __DIR__ . '/../vendor/autoload.php';

// init slim
$app       = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();

// set our json spec in container
$container['json_spec'] = file_get_contents("../misc/json.spec");

// setup db connection
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// setup monolog
$container['logger'] = function($c) {
   $logger       = new \Monolog\Logger('telemetry');
   $file_handler = new \Monolog\Handler\StreamHandler("../data/logs/app.log");
   $logger->pushHandler($file_handler);

   return $logger;
};

//setup flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

//setup Slim\CSRF middleware
$container['csrf'] = function ($c) {
   return new \Slim\Csrf\Guard;
};

// setup twig
$container['view'] = function ($c) {
   $view = new \Slim\Views\Twig('../app/Templates', [
      'cache' => $c['settings']['debug'] ? false : '../data/cache',
      'debug' => $c['settings']['debug']
   ]);

   // Instantiate and add Slim specific extension
   $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
   $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
   $view->addExtension(new Knlv\Slim\Views\TwigMessages(
      new Slim\Flash\Messages()
   ));
   if ($c['settings']['debug']) {
      $view->addExtension(new Twig_Extension_Debug());
   }

   // add some global to view
   $view->getEnvironment()
      // add recaptcha sitekey
      ->addGlobal('recaptchasitekey', $c['settings']['recaptcha']['sitekey']);

   return $view;
};

//setup recaptcha
$container[Captcha::class] = function ($c) {
   return new Captcha($c[ReCaptcha::class]);
};
$container[ReCaptcha::class] = function ($c) {
   return new ReCaptcha($c['settings']['recaptcha']['secret']);
};
$recaptcha = $app->getContainer()->get(Captcha::class);


// error handling
if (!$config['debug']) {
   $container['errorHandler'] = function ($c) { //CUSTOM Error Handler
      return function (\Slim\Http\Request $request,
                       \Slim\Http\Response $response,
                       $exception) use ($c) {
         return $c['view']->render($response,
                                   "errors/server.html",
                                   ["exception" => $exception]);
      };
   };
}

// manage page parameter for Eloquent Paginator
// @see https://github.com/mattstauffer/Torch/blob/master/components/pagination/index.php
Paginator::currentPageResolver(function ($pageName = 'page') {
   $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : 1;
   return $page;
});

// Set up a current path resolver so Eloquent paginator can generate proper links
// @see https://github.com/mattstauffer/Torch/blob/master/components/pagination/index.php
Paginator::currentPathResolver(function () {
   return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
});