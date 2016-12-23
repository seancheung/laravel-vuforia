<?php

namespace Panoscape\Vuforia;

use DateTime;
use DateTimeZone;
use Exception;
use JsonSerializable;
use HTTP_Request2;
use HTTP_Request2_Exception;

/**
* Vuforia Cloud API Service
*
* @method array getTargets()
* @method array getTarget(string $id)
* @method array updateTarget(string $id, mixed $target)
* @method array addTarget(mixed $target)
* @method array deleteTarget(string $id)
* @method array getDuplicates(string $id)
* @method array getDatabaseSummary()
* @method array getTargetSummary(string $id)
*/
class VuforiaWebService
{
    /**
    * VWS targets url
    *
    * @var string
    */
    protected $targets;

    /**
    * VWS duplicates url
    *
    * @var string
    */
    protected $duplicates;

    /**
    * VWS summary url
    *
    * @var string
    */
    protected $summary;

    /**
    * VWS access key
    *
    * @var string
    */
    protected $accessKey;

    /**
    * VWS secret key
    *
    * @var string
    */
    protected $secretKey;

    /**
    * Image naming regex pattern
    *
    * @var string
    */
    protected $namingRule;

    /**
    * Max image size in Bit
    *
    * @var float
    */
    protected $maxImageSize;

    /**
    * Max metadata size in Bit
    *
    * @var float
    */
    protected $maxMetaSize;

    /**
    * Create an instance
    *
    * @param array $config
    * @return void
    */
    function __construct($config)
    {
        $this->targets = array_get($config, 'url.targets');
        $this->duplicates = array_get($config, 'url.duplicates');
        $this->summary = array_get($config, 'url.summary');
        $this->accessKey = array_get($config, 'credentials.access_key');
        $this->secretKey = array_get($config, 'credentials.secret_key');
        $this->namingRule = array_get($config, 'naming_rule');
        $this->maxImageSize = array_get($config, 'max_image_size');
        $this->maxMetaSize = array_get($config, 'max_meta_size');
    }

    /**
    * Create an instance
    *
    * @param array $config
    * @return Eyesar\Vuforia\VuforiaWebService
    */
    static function create($config)
    {
        return new VuforiaWebService($config);
    }


    /**
    * Get all targets
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    */
    function getTargets() {
        return $this->makeRequest($this->targets);
    }
    
    /**
    * Get target by ID
    *
    * @param string $id Target Unique ID
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    */
    function getTarget($id) {
        return $this->makeRequest($this->targets . "/$id");
    }
    
    /**
    * Update target with info by ID
    *
    * @param string $id Target Unique ID
    * @param mixed $target Target to update from
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    */
    function updateTarget($id, $target) {
        if(is_array($target)) {
            $target = new Target($target);
        }
        else if(!($target instanceof Target)) {
            throw new Exception("Invalid target type. Only array and VuforiaWebService/Target are supported");            
        }

        if(!empty($target->name)) {
            if(!empty($this->namingRule) && !preg_match($this->namingRule, $target->name)) {
                throw new Exception("Invalid naming"); 
            }
        }

        if(is_numeric($target->width)) {
            if($target->width <= 0) {
                throw new Exception("Target width should be a number");  
            }
        }

        if(!empty($target->image) && is_numeric($this->maxImageSize) && strlen($target->image) > $this->maxImageSize) {
            throw new Exception("Image is too large"); 
        }

        if(!empty($target->metadata) && is_numeric($this->maxMetaSize) && strlen($target->metadata) > $this->maxMetaSize) {
            throw new Exception("Metadata is too large"); 
        }

        return $this->makeRequest(
            $this->targets . "/$id",
            HTTP_Request2::METHOD_PUT,
            json_encode($target),
            ['Content-Type' => 'application/json']);
    }
    
    /**
    * Add target with info
    *
    * @param mixed $target Target to add
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    */
    function addTarget($target) {
        if(is_array($target)) {
            $target = new Target($target);
        }
        else if(!($target instanceof Target)) {
            throw new Exception("Invalid target type. Only array and VuforiaWebService/Target are supported");            
        }

        if(empty($target->name)) {
            throw new Exception("Target name is required");  
        }

        if(!empty($this->namingRule) && !preg_match($this->namingRule, $target->name)) {
            throw new Exception("Invalid naming"); 
        }

        if(!is_numeric($target->width)) {
            throw new Exception("Target width is required");  
        }

        if($target->width <= 0) {
            throw new Exception("Target width should be a number");  
        }

        if(empty($target->image)) {
            throw new Exception("Target image is required");  
        }

        if(is_numeric($this->maxImageSize) && strlen($target->image) > $this->maxImageSize) {
            throw new Exception("Image is too large"); 
        }

        if(!empty($target->metadata) && is_numeric($this->maxMetaSize) && strlen($target->metadata) > $this->maxMetaSize) {
            throw new Exception("Metadata is too large"); 
        }

        return $this->makeRequest($this->targets,
            HTTP_Request2::METHOD_POST,
            json_encode($target),
            ['Content-Type' => 'application/json']);
    }
    
    /**
    * Delete target by ID
    *
    * @param string $id Target unique ID
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    *
    */
    function deleteTarget($id) {
        return $this->makeRequest($this->targets . "/$id",
            HTTP_Request2::METHOD_DELETE);
    }
    
    /**
    * Get duplicates by ID
    *
    * @param string $id Target unique ID
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    *
    */
    function getDuplicates($id) {
        return $this->makeRequest($this->duplicates . "/$id");
    }
    
    /**
    * Get database summary
    *
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    *
    */
    function getDatabaseSummary() {
        return $this->makeRequest($this->summary);
    }
    
    /**
    * Get target summary
    *
    * @param string $id Target unique ID
    *
    * @return array
    *
    * @example ['status' => 200, 'body' => "--JSON--"]
    * @example ['status' => 400, 'body' => "--ERROR--"]
    * @example ['status' => 500, 'body' => "--EXCEPTION--"]
    *
    */
    function getTargetSummary($id) {
        return $this->makeRequest($this->summary . "/$id");
    }
    
    private function makeRequest($url, $method = HTTP_Request2::METHOD_GET, $body = null, $headers = null) {
        
        if(empty($this->accessKey) || empty($this->secretKey)) {
            throw new Exception('Missing Vuforia Access/Secret Key(s)');
        }
        
        $request = new HTTP_Request2();
        $request->setMethod($method);
        $request->setConfig(['ssl_verify_peer' => false]);
        $request->setURL($url);
        if(!empty($body)) {
            $request->setBody($body);
        }
        
        //set header
        $date = (new DateTime("now", new DateTimeZone("GMT")))->format("D, d M Y H:i:s") . " GMT";
        $request->setHeader('Date', $date);

        if(!empty($headers)) {
            foreach ($headers as $key => $value) {
                $request->setHeader($key, $value);
            }
        }

        $signature = '';
        try {
            $signature = $this->getSignature($request, $this->secretKey);
        }
        catch(Exception $e) {
            return [
            'status' => 500,
            'body' => $e->getMessage()
            ];
        }
        
        $request->setHeader("Authorization" , "VWS " . $this->accessKey . ":" . $signature);

        try {
            $response = $request->send();
            
            return [
            'status' => $response->getStatus(),
            'body' => $response->getBody()
            ];
        }
        catch(HTTP_Request2_Exception $e) {
            return [
            'status' => 500,
            'body' => $e->getMessage()
            ];
        }
    }
    
    private function getSignature($request, $secretKey){
        
        $method = $request->getMethod();
        
        // The HTTP Header fields are used to authenticate the request
        $requestHeaders = $request->getHeaders();
        
        // note that header names are converted to lower case
        $dateValue = $requestHeaders['date'];
        
        $requestPath = $request->getURL()->getPath();
        
        $contentType = '';
        // Not all requests will define a content-type
        if( isset( $requestHeaders['content-type'] )) {
            $contentType = $requestHeaders['content-type'];
        }
        
        $hexDigest = 'd41d8cd98f00b204e9800998ecf8427e';
        if ( $method == 'GET' || $method == 'DELETE' ) {
            // Do nothing because the strings are already set correctly
        }
        else if ( $method == 'POST' || $method == 'PUT' ) {
            // If this is a POST or PUT the request should have a request body
            $hexDigest = md5( $request->getBody() , false );
        }
        else {
            throw new Exception("ERROR: Invalid content type");
        }
        
        $toDigest = "$method\n$hexDigest\n$contentType\n$dateValue\n$requestPath";
        
        $shaHashed = '';
        
        // the SHA1 hash needs to be transformed from hexidecimal to Base64
        $shaHashed = $this->hexToBase64( hash_hmac("sha1", $toDigest , $secretKey) );
        
        return $shaHashed;
    }
    
    private function hexToBase64($hex){
        
        $return = '';
        
        foreach(str_split($hex, 2) as $pair){
            $return .= chr(hexdec($pair));
        }
        
        return base64_encode($return);
    }
}

/**
* Vuforia Webservice Target
*
* @property string $name Target name
* @property float $width Target width
* @property mixed $image Target image(optional)
* @property boolean Target active state(optional)
* @property mixed Target metadata(optional)
*/
class Target implements JsonSerializable
{
    /**
    * Target name
    *
    * @var string
    */
    public $name;

    /**
    * Target width
    *
    * @var float
    */
    public $width;

    /**
    * Target image file content
    *
    * @var mixed $image
    */
    public $image;

    /**
    * Target active state(optional)
    *
    * @var boolean
    */
    public $active;

    /**
    * Target metadata content(optional)
    *
    * @var mixed
    */
    public $metadata;

    /**
    * Constructor
    *
    * @param array $attributes Array to fill properties with
    */
    function __construct($attributes = null)
    {
        if(!empty($attributes)) {
            
            if(array_key_exists('name', $attributes)) {
                $this->name = $attributes['name'];
            }

            if(array_key_exists('width', $attributes)) {
                $this->width = $attributes['width'];
            }
            
            if(array_key_exists('image', $attributes)) {
                $this->image = $attributes['image'];
            }
            else if(array_key_exists('path', $attributes)) {
                try 
                {
                    $this->image = file_get_contents($attributes['path']);
                }
                catch(Exception $e)
                {
                    throw new Exception("Failed to read image from " . $attributes['path'] . ': ' . $e->getMessage());                    
                }
            }

            if(array_key_exists('active', $attributes)) {
                $this->active = $attributes['active'];
            }

            if(array_key_exists('metadata', $attributes)) {
                $this->metadata = $attributes['metadata'];
            }
        }        
    }
    
    public function jsonSerialize() {
        
        $array = [];

        if(!empty($this->name)) {
            $array['name'] = $this->name;
        }

        if(is_numeric($this->width)) {
            $array['width'] = $this->width;
        }

        if(!empty($this->image)) {
            $array['image'] = base64_encode($this->image);
        }
        
        if(is_bool($this->active)) {
            $array['active_flag'] = $this->active ? 1 : 0;
        }
        
        if(!empty($this->metadata)) {
            $array['application_metadata'] = base64_encode($this->metadata);
        }
        
        return $array;
    }
}
