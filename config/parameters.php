<?php

return $parameters = [
    'db' => [
        'hostname' => 'mysql:host=localhost;dbname=your_dbname',
        'username' => 'your_username',
        'password' => 'your_db_password',
        ],
    'swift_mailer' => [
        'host' => 'smtp_mail',
        'port' => 'some_port',
        'encryption' => 'some_encryption',
        'sender_address' => '',
        'delivery_address' => [''],
        'password' => 'password'
    ],
    'memcached' => [
        'host' => '',
        'port' => 'some_port'
    ],
    'logger' => [
        'path' => 'path_to_log_file',
        'type' => 'file_or_db',
    ]
];