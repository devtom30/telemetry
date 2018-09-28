<?php

return $config = [
   'project' => [
       'name'   => 'Kimios',
       'url'    => 'http://kimios.com',
       'dashboard' => [
          'leaflet' => [
             'provider'  => 'OpenStreetMap.BlackAndWhite'
          ],

       ],
        'enable_contact' => false,
        'references' => [
            'num_users' => [
                'label' => 'Number of users',
                'short label' => 'Users'
            ],
            'num_documents' => [
                'label' => 'Number of documents',
                'short label' => 'Documents'
            ]
        ],
        'schema' => [
            'plugins' => false
        ]
    ],
   'baseurl' => 'http://localhost/telemetry/',
   'db' => [
      'driver'    => "pgsql",
      'host'      => "localhost",
      'database'  => "telemetry",
      'username'  => "telemetry",
      'password'  => "telemetry",
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => '',
   ],
   'recaptcha' => [
      'sitekey'   => 'xxxx',
      'secret'    => 'xxxx'
   ],
   'mail_admin' => '',
   'mail_from' => 'kimios@teclib.com',
   'debug' => true,
   'displayErrorDetails' => true,
   'addContentLengthHeader' => false,
];