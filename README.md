# Short Url Generator

Short Url Generator is package to generate tiny url from long url.This package consists of two apis one for generating the short url and other for redirecting to that orignal url using the short url. Detailed description of apis will given in the usages section.In first api i am generating a random 8 digit string corresponding to the input url and storing in the database.Once the short url generated response will be given with success and short url and in second you have to hit that short url using your browser and you will be redirected to original url. As its a assignment project i handled some cases and some i thought can be handled more like if short url is already existed for the input url no need to generate new one.One more functionality is also there user can short url can be used one time for that one more parameter has to be sent will describe in usages section.Some more functionality we can implement like short url is only accessible by single user.

## Installation

Git clone

```bash
git clone https://github.com/vishalspn/newsbytes-short-url.git
```
### DataBase

Crate database newsbytes using the following command

```bash
CREATE DATABASE newsbytes;
```

Now create a table with following command

```bash
CREATE TABLE `short_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `s_url` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `single_use` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
```
Database configuration setting
```bash
    Go to database/datbase.php and edit the following settings as per your configuration
        $userName = "root";
        $password = "root"; 
```

## Usage
```bash
   Api for Generating Short Url;
```
```php
    Url : http://localhost/newsbytes-short-url/api/createlink
    Payload : { "url" : "https://google.com","single_time_use_flag": 1}
    Request Type : POST
    Response : {
                "status": "success",
                "code": 200,
                "short_url": "localhost/Vq89X4Tt",
                "message": "Url generated successfully"
               }
   Description : You have to hit this api with input parameters as a
                 POST request,flag single_time_use_flag accepts two values
                 0 and 1 ,1 means url is allowed to use only single time and
                 zero means url can be accessible all time
```
```bash
   Api for Getting Orignal Url;
```
```php
    Url : http://localhost/newsbytes-short-url/Vq89X4Tt
    Request Type : Get
    Description : You have to hit this api from browser to get redirected
                  to the original url , if short url is not found you will
                  404 error on browser else it will redirect to you to 
                  destination url.  


