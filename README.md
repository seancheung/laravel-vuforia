# Laravel-Vuforia
Vuforia web service API for Laravel

## Install

```shell
composer require panoscape/laravel-vuforia
```

Service provider

> config/app.php

```php
'providers' => [
    ...
    Panoscape\Vuforia\VuforiaWebServiceProvider::class,
];
```

Facades

> config/app.php

```php
'aliases' => [
    ...
    'VWS' => Panoscape\Vuforia\Facades\VuforiaWebService::class,
];
```

## Features

### Easy with Facade:
- VWS::getTargets()
- VWS::getTarget(string $id)
- VWS::updateTarget(string $id, mixed $target)
- VWS::addTarget(mixed $target)
- VWS::deleteTarget(string $id)
- VWS::getDuplicates(string $id)
- VWS::getDatabaseSummary()
- VWS::getTargetSummary(string $id)

### Call with array
```php
VWS::addTarget([
    'name' => 'my_new_target', 
    'width' => 320, 
    'path' => public_path('storage/IMG_2738.jpg')
    ]); 
```

### Pre-Check
```php
 VWS::addTarget(['name' => '123 1']);
```
> Exception with message 'Invalid naming'

```php
VWS::addTarget(['name' => '123']);
```
> Exception with message 'Target width is required'

```php
VWS::addTarget(['name' => '1231', 'width' => 100, 'path' => public_path('storage/image.png')]);
```
> Exception with message 'Image is too large'


### Image Target Class

```php
$target = new \Panoscape\Vuforia\Target;
$target->name = 'image_01'; 
$target->width = 320; 
$target->image = file_get_contents(public_path('images/001.jpg')); 
//optional fields
$target->metadata = 'Hello world'; 
$target->active = false; 
```

### Wrapped result

```php
[
    "status" => 201,
    "body" => '{"result_code":"TargetCreated","transaction_id":"xxx","target_id":"xxx"}',
]
```

```php
[
    "status" => 422,
    "body" => '{"result_code":"BadImage","transaction_id":"xxx"}',
]  
```

### Configurable
```php
[
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
    | Max image size(unencoded) in Byte. Default is 2MB
    | Set to null to bypass size checking(not recommended)
    |--------------------------------------------------------------
    |
    |
    */
    'max_image_size' => 2097152,

    /*
    |--------------------------------------------------------------
    | Max metadata size(unencoded) in Byte. Default is 2MB
    | Set to null to bypass size checking(not recommended)
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
    | Set to null to bypass name checking(not recommended)
    |--------------------------------------------------------------
    |
    |
    */
    'naming_rule' => '/^[\w\-]+$/'
]
```

### Jobs and Notification
```php
/**
* Upload image to Vuforia
*/
abstract class VuforiaJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    /**
    * Execute the job.
    *
    * @param  VuforiaWebService  $vws
    * @return void
    */
    abstract function handle(VuforiaWebService $vws);
    
    /**
    * The job failed to process.
    *
    * @param  Exception  $exception
    * @return void
    */
    abstract function failed(Exception $exception);
}
```
```php
abstract class VuforiaNotification extends Notification
{
    use Queueable;

    protected $result;

    /**
     * Create a new notification instance.
     *
     * @param mixed $result
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'result' => $this->result
        ];
    }
}
```

## Documentation
See [Wiki](https://github.com/seancheung/laravel-vuforia/wiki)
