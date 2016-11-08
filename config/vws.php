<?php

return [

    /*
    |--------------------------------------------------------------
    | Vuforia Web Service URLs
    |--------------------------------------------------------------
    |
    |
    */
    'url' => [
        /*
        |--------------------------------------------------------------
        | Vuforia Web Service Targets Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'targets' => 'https://vws.vuforia.com/targets',

        /*
        |--------------------------------------------------------------
        | Vuforia Web Service Duplicates Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'duplicates' => 'https://vws.vuforia.com/duplicates',

        /*
        |--------------------------------------------------------------
        | Vuforia Web Service Duplicates Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'summary' => 'https://vws.vuforia.com/summary',
        ],

    /*
    |--------------------------------------------------------------
    | Vuforia cloud database access key and secret key
    |--------------------------------------------------------------
    |
    |
    */
    'credentials' => [
        "access_key" => env('VUFORIA_ACCESS_KEY'),
        "secret_key" => env('VUFORIA_SECRET_KEY'),
    ]
];