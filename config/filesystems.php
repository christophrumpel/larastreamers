<?php

return [

    'disks' => [
        'backupFolder' => [
            'driver'   => 'ftp',
            'host'     => 'nomoreencore.com',
            'username' => env('BACKUP_FTP_USER'),
            'password' => env('BACKUP_FTP_PW'),
        ],
    ],

];
