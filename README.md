# Laravel-Vuforia
Vuforia web service API for Laravel

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
$target = new \Eyesar\Vuforia\Target;
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

## Documentation
See [Wiki](https://github.com/Eyesar/laravel-vuforia/wiki)
