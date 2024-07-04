<?php

return [
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
    ],
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    'employee_photos' => [
        'driver' => 'local',
        'root' => storage_path('app/employee_photos'),
        'url' => env('APP_URL').'/storage/employee_photos',
        'visibility' => 'public',
    ],
],

];