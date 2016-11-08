# Vuforia Web Service



## Add Target

You can add targets to a Cloud Database using the Vuforia Web Services REST API, by making an HTTP POST request to [https://vws.vuforia.com/targets](https://vws.vuforia.com/targets). The request header should include the authorization fields described in the article [Vuforia Web Services](https://library.vuforia.com/articles/Training/Using-the-VWS-API) guide, and it should declare an application/JSON content type. The body of the request must be a JSON object that defines the properties of the targets as specified here.

> Vuforia's Cloud Recognition service lets you add a digital content payload to your target. You can include up to 1 MB of base 64 encoded content as metadata attached to the target's search result. Examples of this content could be images or mobile-optimized 3D models. This feature lets you host your content with your targets in the Cloud Recognition service.
>
> The developer CMS performs an HTTPS POST on *https://vws.vuforia.com/targets*

### REQUEST

#### Sample

```http
POST /targets HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2012 12:45:19 GMT
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
Content-Type: application/json
{
  "name":"tarmac",
  "width":32.0,
  "image":"0912ba39x...",
  "application_metadata":"496fbb6532b3863460a984de1d980bed5ebcd507"
}
```

#### JSON Elements

| Field name           | Type                                     | Mandatory | Description                              |
| -------------------- | ---------------------------------------- | --------- | ---------------------------------------- |
| name                 | String [1 - 64]                          | Yes       | Name of the target, unique within a database |
| width                | Float                                    | Yes       | Width of the target in scene unit        |
| image                | Base64 encoded binary image file in JPG or PNG format | No        | Contains the base64 encoded binary recognition image data |
| active_flag          | Boolean                                  | No        | Indicates whether or not the target is active for query |
| application_metadata | Base64 encoded data                      | No        | The base64 encoded application metadata associated with the target |



## Check for Duplicate

The developer’s CMS performs an HTTPS GET on *https://vws.vuforia.com/duplicates/{target_id}* with the header that contains the usual HTTP header fields, plus the Authorization field.

> The cloud service searches the database for images that can be considered duplicates, such as images that are the same as the one referenced, as identified by a given target_id.
>
> 1. The duplicate check can be called as soon as a target’s upload action has finished using POST or PUT. The target referenced by target_id does not have to be in **active** state to perform the duplicate target check.
> 2. If a target is explicitly inactivated through the VWS API (or through the Target Manager), then this target is no longer taken into account for the duplicate target check.
> 3. If the requested target does not exist, the service returns an **UnknownTarget (404)** error in the POST response body.

### REQUEST

#### Sample

```http
GET /duplicates/550e8400e29b41d34716446655834450 
HTTP/1.1Host: vws.vuforia.comDate: Mon, 23 Apr 2012 12:45:19 
GMTAuthorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
```

### RESPONSE

The response returns a list of similar targets, if any are available. The maximum number of similar targets is 16. 

If the successful answer does not contain duplicates, it means that no similar targets were found.
If the requested target does not exist, the error **UnknownTarget (404)** is returned in the response body.

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "similar_targets":[
    "550e8400e29b41d4a716446655447300",
    "578fe7fd60055cbc84c2d215066b7a9d"
  ]
}
```

#### JSON Elements

| Field Name      | Type             | Mandatory | Description                              |
| --------------- | ---------------- | --------- | ---------------------------------------- |
| similar_targets | targetID [0..16] | Yes       | List of possible duplicate targets – target_id’s for duplicate targets |

## Update Target

To update a target in a database, the CMS must perform an HTTPS PUT on *https://vws.vuforia.com/targets/{target_id}*

- A header that contains the usual HTTP header fields, plus the Authorization field
- The JSON body that contains the updated target information, including the image. The URL must replace the {target_id} with the actual ID of the target, which is returned when the target was created. This is shown in the **UpdateTarget.java** sample.

### REQUEST

#### Sample

```http
PUT /targets HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2013 12:45:19 GMT
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
Content-Type: application/json
{
  "name":"tarmac",
  "width":32.0,
  "image":"0912ba39x...",
  "active_flag":true,
  "application_metadata":"496fbb6532b3863460a984de1d980bed5ebcd507"
}
```

#### JSON Elements

| Field name           | Type                                     | Mandatory | Description                              |
| -------------------- | ---------------------------------------- | --------- | ---------------------------------------- |
| name                 | String [1 - 64]                          | No        | Name of the target, unique within a database |
| width                | Float                                    | No        | Width of the target in scene unit        |
| image                | Base 64 encoded binary image file in JPG or PNG format | No        | Contains the base 64 encoded binary recognition image data |
| active_flag          | Boolean                                  | No        | Indicates whether or not the target is active for query |
| application_metadata | Base 64 encoded data                     | No        | The base 64 encoded application metadata associated with the target |

### RESPONSE

The response contains the ID of the resulting target.
Confirm that the status is **Success**; otherwise an error has occurred.

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "result_code":"Success",
  "transaction_id":"550e8400e29b41d4a716446655482752"
}
```

#### JSON Elements

| Field              | Type                       | Mandatory | Description                              |
| ------------------ | -------------------------- | --------- | ---------------------------------------- |
| result_code        | String [1 - 64]            | Yes       | One of the Vuforia Common Result Codes.See [How To Interpret VWS API Result Codes](https://library.vuforia.com/articles/Solution/How-To-Interperete-VWS-API-Result-Codes) |
| transaction_id/td> | 32-character String (UUID) | Yes       | ID of the transaction                    |



## Retrieve Target

To get information on a specific target in a database, the CMS needs to perform a HTTPS GET on *https://vws.vuforia.com/targets/{target_id}*, (where {target_id} is replaced with the actual ID of the target), with the header containing the usual HTTP header fields plus the Authorization field. This is shown in the UpdateTarget.java sample.

### REQUEST

#### Sample

```http
GET /targets/550e8400e29b41d34716446655834450 HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2013 12:45:19 GMT
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
```

### RESPONSE

After the authentication has been confirmed, the Cloud Recognition Service returns a JSON message with the contents of the specified target. The developer’s CMS should check to make sure that the result_code indicates Success. Otherwise, there was a problem retrieving the list.

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "result_code":"Success",
  "transaction_id":"e29b41550e8400d4a716446655440000",
  "target_record":{
    "target_id":"550b41d4a7164466554e8400e2949364",
    "active_flag":true,
    "name":"tarmac",
    "width":100.0,
    "tracking_rating":4,
    "reco_rating":""
  },
  "status":"Success"
}
```

#### JSON Elements

| Field name     | Type                       | Mandatory | Description                              |
| -------------- | -------------------------- | --------- | ---------------------------------------- |
| result_code    | String [1 - 64]            | Yes       | One of the result codes defined in Common Result Codes. See: [How To Interpret VWS API Result Codes](https://library.vuforia.com/articles/Solution/How-To-Interperete-VWS-API-Result-Codes) |
| transaction_id | 32-character String (UUID) | Yes       | ID of the transaction                    |
| target_record  | **TargetRecord**           | Yes       | Contains a target record at the TMS      |
| status         | String [1-64]              | Yes       | Status of the target; current supported values are “processing,” “success,” and “failed” |

##### Target Record

The target_record body is defined as listed in the following table

| Field name      | Type                       | Mandatory | Description                              |
| --------------- | -------------------------- | --------- | ---------------------------------------- |
| target_id       | 32-character String (UUID) | Yes       | Target_id of the target                  |
| active_flag     | Boolean                    | No        | Indicates whether or not the target is active for query; the default is true |
| name            | String [1-64]              | Yes       | Name of the target; unique within a database |
| width           | Float                      | Yes       | Width of the target in scene unit        |
| tracking_rating | Int [0 - 5]                | Yes       | Rating of the target recognition image for tracking purposes |
| reco_rating     | string                     | No        | Rating of the target recognition image for recognition purposes – an empty string for now |



# Get Target List

To get a listing of all of the target_ids for a database, the CMS performs a HTTPS GET on*https://vws.vuforia.com/targets*

### REQUEST

#### Sample

```http
GET /targets HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2013 12:45:19 GMT
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
```

### RESPONSE

After the authentication has been confirmed, the Cloud Recognition Service returns a JSON message with a list of all targets in the database. The CMS should check to make sure that the result_code indicates **Success**. Otherwise, there was a problem retrieving the list.

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "result_code":"Success",
  "transaction_id":"550e8400e29440000b41d4a716446655",
  "results":[
    "00550e84e29b41d4a71644665555678",
    "578fe7fd60055a5a84c2d215066b7a9d"
  ]
}
```

#### JSON Elements

| Field          | Type                       | Mandatory | Description                              |
| -------------- | -------------------------- | --------- | ---------------------------------------- |
| result_code    | String [1 - 64]            | Yes       | One of the Vuforia Common Result Codes.See: [How To Interpret VWS API Result Codes](https://library.vuforia.com/articles/Solution/How-To-Interperete-VWS-API-Result-Codes) |
| transaction_id | 32-character String (UUID) | Yes       | ID of the transaction                    |
| results        | target_id [0..unbounded]   | Yes       | List of target IDs for the target in the developer project |



# Delete Target

To delete a target in a database, the CMS needs to perform a HTTPS DELETE on *https://vws.vuforia.com/targets/{target_id}*, with a header that contains the usual HTTP header fields, plus the Authorization field, and no body.

The URL must have the {target_id} replaced with the actual ID of the target, which is returned when the target gets created.

This code is shown in the DeleteTarget.java sample.

**Note:** Targets with a processing status cannot be deleted.

> Confirm that a target's status is success before trying to delete the target. See [How to Retrieve a Target Using the VWS API](https://library.vuforia.com/articles/Training/How-to-Retrieve-a-Target-Using-the-VWS-API) for details on how to query the target status using Get /targets/{target_id}. The value of active_flag and rating are not valid until the status value is either success or failed.

### REQUEST

The response contains the status of the deletion. Confirm that the status is Success. Otherwise an error has occurred.

#### Sample

```http
DELETE /targets/550e8400e29b41d34716446655834450  HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2013 12:45:19 GMT 
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
```

### RESPONSE

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "result_code":"Success",
  "transaction_id":"550e8400e29b41d4a716446655482752"
}
```

#### JSON Elements

| Field          | Type                       | Mandatory | Description                              |
| -------------- | -------------------------- | --------- | ---------------------------------------- |
| result_code    | String [1 - 64]            | Yes       | One of the Vuforia Common Result Codes.See: [VWS Result Codes](https://library.vuforia.com/articles/Training/VWS-Result-Codes) |
| transaction_id | 32-character String (UUID) | Yes       | ID of the transaction                    |



# Get Database Summary

To get a summary of images from a cloud database, you must ensure that the CMS performs a HTTPS GET on *https://vws.vuforia.com/summary*

### REQUEST

#### Sample

```http
GET /summary  HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2013 12:45:19 GMT
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
```

### RESPONSE

After the authentication has been confirmed, the Cloud Recognition Service returns a JSON message with the contents of the specified target. The CMS should check to make sure that the result_code indicates **Success**. Otherwise there was a problem retrieving the list.

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "result_code":"Success",
  "transaction_id":"00e2550e849b41d4a755440000164466",
  "name":"RecoTest",
  "active_images":3,
  "inactive_images":0,
  "failed_images":0
}
```

#### JSON Elements

| Field name      | Type                       | Mandatory | Description                              |
| --------------- | -------------------------- | --------- | ---------------------------------------- |
| result_code     | String [1 - 64]            | Yes       | One of the result codes defined in Common Result Codes. See: [VWS Result Codes](https://library.vuforia.com/articles/Training/VWS-Result-Codes) |
| transaction_id  | 32-character String (UUID) | Yes       | ID of the transaction                    |
| name            | String [1 - 64]            | Yes       | Name of the database                     |
| active_images   | uint32                     | Yes       | Total number of images with active_flag = true, and status = success for the database |
| inactive_images | uint32                     | Yes       | Total number of images with active_flag = false, and status = **success** for the database |
| failed_images   | uint32                     | Yes       | Total number of images with status = **fail** for the data |



# Retrieve Target Summary

To get a summary of a specific image in a database, the CMS must perform a HTTPS GET on *https://vws.vuforia.com/summary/{target_id}*

### REQUEST

#### Sample

```http
GET /summary/550e846655447733400e29b41d4a7164 HTTP/1.1
Host: vws.vuforia.com
Date: Mon, 23 Apr 2013 12:45:19 GMT
Authorization: VWS df8d23140eb443505c0661c5b58294ef472baf64:jHX6oLeqTXpynyqcvVC2MSHarhU
```

### RESPONSE

After the authentication has been confirmed, the Cloud Recognition Service returns a JSON message with the contents of the specified target. The CMS should make sure that the result_code indicates **Success**. Otherwise there was a problem retrieving the list.

#### Sample

```http
HTTP/1.1 200 OK
Content-Type: application/json
{
  "result_code":"Success",
  "transaction_id":"d4a716446655440663390e8400e29b41",
  "database_name":"RecoTest",
  "target_name":"tarmac",
  "upload_date":"2012-03-31",
  "active_flag":true,
  "status":"success",
  "tracking_rating":4,
  "reco_rating":"",
  "total_recos":0,
  "current_month_recos":0,
  "previous_month_recos":0
}
```

#### JSON Elements

**Note**: tracking_rating and reco_rating are provided only when status = **success**.

|                      |                            |           |                                          |
| -------------------- | -------------------------- | --------- | ---------------------------------------- |
| Field name           | Type                       | Mandatory | Description                              |
| result_code          | String [1 - 64]            | Yes       | One of the result codes defined in Common Result Codes.See: [VWS Result Codes](https://library.vuforia.com/articles/Training/VWS-Result-Codes) |
| transaction_id       | 32-character String (UUID) | Yes       | ID of the transaction                    |
| database_name        | String [1 - 64]            | Yes       | Name of the database                     |
| target_name          | String [1 - 64]            | Yes       | Name of the target                       |
| upload_date          | Date                       | Yes       | Date of the upload (specified as YYYY-MM-DD) |
| active_flag          | Boolean                    | Yes       | Indicates whether or not the target is active for query; default is true |
| status               | String [1- 64]             | Yes       | Status of the target; current supported values are **processing**, **success**, and **failure** |
| tracking_rating      | Int [0 - 5]                | No*       | Rating of the target recognition image for tracking purposes |
| reco_rating          | String                     | No*       | Currently an empty string                |
| total_recos          | Int                        | No*       |                                          |
| current_month_recos  | Int                        | No*       |                                          |
| previous_month_recos | Int                        | No*       |                                          |