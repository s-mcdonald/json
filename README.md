# PHPJson
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Json)
![License](https://img.shields.io/github/license/s-mcdonald/phpjson)
![PHP Compatibility](https://img.shields.io/badge/php-%3E%3D8.2.0-blue)

![Master Build Status](https://img.shields.io/github/actions/workflow/status/s-mcdonald/phpjson/php.yml?branch=master&label=master)
![Develop Build Status](https://img.shields.io/github/actions/workflow/status/s-mcdonald/phpjson/php.yml?branch=develop&label=develop)

_A Fast and Lightweight PHP JSON Object Serialization Library._

## 💖 Support This Project
This project is supported by your donations! Click the **[Sponsor](https://github.com/sponsors/s-mcdonald)** link to display funding options.

___


This library enables the Serializing of PHP Objects/Classes. It also contains utility features for working with JSON structures.

Attributes are the default mechanism to map POPO fields to and from JSON for Serialization as they allow for quick and robust implementation.

While attributes are the default way to map fields in PHPJson. This library allows for the use of a JSON configuration file to be passed to the serializer as an alternative method. 


## Contents

* [Usage](#Usage)
  * [Serialization](#serialization)
    * [Basic usage](#basic-usage)
    * [Override Json Properties](#override-json-properties)
    * [Nested Structures](#nested-structures)
    * [Serializing Enums](#serializing-enums)
  * [Hydration/Deserialization](#deserialize-aka-object-hydration)
  * [JsonBuilder](#jsonbuilder)
  * [Json Formatting](#json-formatting)
* [Installation](#installation)
* [Dependencies](#dependencies)
* [License](#license)
* [Contribute](#contribute)


# Serialization

## Basic usage

```php
echo Json::serialize($user);

// Or
$serializer = new JsonSerializer();
echo $serializer->serialize($user);

class User
{
    #[JsonProperty]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;    
    
    private int $creditCard;
}
```
```json
{
    "name": "Foo",
    "phoneNumbers": [
        "044455444",
        "244755465"
    ]
}
```


## Override Json Properties
You can override the property or method names by supplying your own. This also works in reverse for hydration.

```php
class User
{
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty('numbers')]
    public array $phoneNumbers;    
    
    private int $creditCard;
}
```
```json
{
    "userName": "Foo",
    "numbers": [
        "044455444",
        "244755465"
    ]
}
```

Example using methods
```php
class User
{
    #[JsonProperty('creditCardNumber')]
    public function getCreditCard(): int
    {
        return $this->creditCard;
    }
}
```
```json
{
    "creditCardNumber": "55044455444677"
}
```

## Nested Structures

Given the below classes, Json will serialize them into a single Json object with class nesting as required.

Take the below code for example:

```php
$sut = new ParentClass();
$sut->name = 'fu';
$sut->someChild = new ChildClass("bar");
```

```php
class ParentClass
{
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty('child')]
    public ChildClass $someChild;
}

class ChildClass
{
    public function __construct(
        #[JsonProperty('childProp')]
        private string $childProperty,
    ){
    }
}
```
```json
{
    "userName": "fu",
    "child": {
        "childProp": "bar"
    }
}
```


## Serializing Enums

PHPJson also supports serializing enums, pure and backed enums.

```php
enum Status 
{
    case Enabled;
    case Disabled;
}

echo Json::serialize(Status::Enabled);
```
```json
{
    "Status": "Enabled"
}
```

# Deserialize aka Object Hydration
For simple Hydration, you do not need to implement any attributes or have a mapping for properties, as long as the Class you use has the same properties within your json, PHPJson will hydrate your class or entity.

PHP
```php
class MyUser 
{
    public string $name;
    public int $age;
    public bool $isActive;
}
```

JSON
```json
{
  "name": "Freddy",
  "age": 35,
  "isActive": true
}
```


Now deserialize the json string with the PHP class.
```php
$myUser = Json::deserialize($json, MyUser::class);
```
```
YourNamespace\MyUser Object (
    'name' => 'Freddy'
    'age' => 35
    'isActive' => true
)
```

```php
class MyUser 
{
    #[JsonProperty('name')]
    public string $userName;

    public int $age;
    public bool $isActive;
}
```


# JsonBuilder
Fluently create Json objects using PHP.

```php

echo Json::createJsonBuilder()
        ->addProperty('id', 11)
        ->addProperty('title', "Perfume Oil")
        ->addProperty('rating', 4.26)
        ->addProperty('stock', 65)
        ->addProperty(
            'thumbnail',
            Json::createJsonBuilder()
                ->addProperty("url", "https://i.dummyjson.com/data/products/11/thumbnail.jpg")
                ->addProperty("title", "thumbnail.jpg")
        )
        ->addProperty("images", [
            "https://i.dummyjson.com/data/products/11/1.jpg",
            "https://i.dummyjson.com/data/products/11/2.jpg"
        ])
;
```
```json
{
    "id": 11,
    "title": "Perfume Oil",
    "rating": 4.26,
    "stock": 65,
    "thumbnail": {
    "url": "https://i.dummyjson.com/data/products/11/thumbnail.jpg",
          "title": "thumbnail.jpg"
    },
    "images": [
          "https://i.dummyjson.com/data/products/11/1.jpg",
          "https://i.dummyjson.com/data/products/11/2.jpg"
     ]
}
```

# Json Formatting
Prettify or Uglify your json strings

```php
Json::prettify('{"name":"bar","age":34}')
```
```json
{
    "name": "bar",
    "age": 34
}
```
and then the reverse
```php
Json::uglify('{
    "name": "bar",
    "age": 34
}') 
```
```json
{"name":"bar","age":34}
```

# Reference

## Installation
Install this package via composer, or simply fork/clone the repo.
```bash
composer require s-mcdonald/phpjson
```

## Dependencies

*  PHP 8.2, 8.3, 8.4

## License

Json is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).

## Contribute
🙌 Want to contribute? 

Check out the issues section to get started.

