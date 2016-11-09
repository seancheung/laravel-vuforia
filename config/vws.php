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
        | Vuforia Web Service Summary Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'summary' => 'https://vws.vuforia.com/summary',
        ],

    /*
    |--------------------------------------------------------------
    | Vuforia cloud database credentials
    |--------------------------------------------------------------
    |
    |
    */
    'credentials' => [
        /*
        |--------------------------------------------------------------
        | Vuforia cloud database access key
        |--------------------------------------------------------------
        |
        |
        */
        "access_key" => env('VUFORIA_ACCESS_KEY'),

        /*
        |--------------------------------------------------------------
        | Vuforia cloud database secret key
        |--------------------------------------------------------------
        |
        |
        */
        "secret_key" => env('VUFORIA_SECRET_KEY'),
    ],

    /*
    |--------------------------------------------------------------
    | Max image size(unencoded) in Bit. Default is 2MB
    | Set to null to bypass size checing(not recommended)
    |--------------------------------------------------------------
    |
    |
    */
    'max_image_size' => 2097152,

    /*
    |--------------------------------------------------------------
    | Max metadata size(unencoded) in Bit. Default is 2MB
    | Set to null to bypass size checing(not recommended)
    |--------------------------------------------------------------
    |
    |
    */
    'max_meta_size' => 2097152,

    /*
    |--------------------------------------------------------------
    | Name checking rule. Default is 
    | no spaces and may only contain: 
    | numbers (0-9), letters (a-z), underscores ( _ ) and dashes ( - )
    | Set to null to bypass size checing(not recommended)
    |--------------------------------------------------------------
    |
    |
    */
    'naming_rule' => '/^[\w\-]+$/'
];