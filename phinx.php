<?php
return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_environment' => getenv('PHINX_ENV') ?: 'dev_db',
        'dev_db' => [
            'adapter' => 'mysql',
            'host' => getenv('MYSQL_HOST'),
            'name' => getenv('MYSQL_DATABASE'),
            'user' => getenv('MYSQL_USER'),
            'pass' => getenv('MYSQL_PASSWORD'),
            'port' => getenv('MYSQL_PORT'),
            'charset' => 'utf8mb4',
        ],
    ],
];