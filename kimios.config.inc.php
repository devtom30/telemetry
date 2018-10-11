<?php

return $config = [
   'project' => [
       'name'   => 'Kimios',
       'url'    => 'http://kimios.com',
       'dashboard' => [
          'leaflet' => [
             'provider'  => 'OpenStreetMap.BlackAndWhite'
          ],
          'php_versions' => [
              'language_name' => 'Java'
          ]
       ],
        'enable_contact' => false,
        'dyn_references' => [
            'num_users' => [
                'label' => 'Number of users',
                'short_label' => '#Users'
            ],
            'num_documents' => [
                'label' => 'Number of documents',
                'short_label' => '#Documents'
            ]
        ],
        'schema' => [
            'plugins' => false,
            'usage' => [
                'num_users'       => [
                    'type'      => 'integer',
                    'required'  => true
                ],
                'num_documents'  => [
                    'type'      => 'integer',
                    'required'  => true
                ],
                'num_documents_per_user'   => [
                    'type'      => 'integer',
                    'required'  => true
                ]
            ]
        ],
        'mapping' => [
            'num_users'       => 'glpi_avg_entities',
            'num_documents' => 'glpi_avg_computers',
            'num_documents_per_user'  => 'glpi_avg_networkequipments'
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