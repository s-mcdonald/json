# PHPJson
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Json)
[![Source](https://img.shields.io/badge/license-MIT-gold.svg)](https://github.com/s-mcdonald/Json)
![PHP Compatibility](https://img.shields.io/badge/php-%3E%3D8.2.0-blue)
![Build Status](https://img.shields.io/github/workflow/status/s-mcdonald/json/Tests)

_A modern PHP JSON Object Serialization Library._

## 💖 Support This Project
This project is supported by your donations! Click the **[Sponsor](https://github.com/sponsors/s-mcdonald)** link to display funding options.




This library enables the Serializing of PHP Objects/Classes. It also contains utility features for working with JSON structures.

Attributes are the default mechanism to map POPO fields to and from JSON for Serialization as they allow for quick and robust implementation.

While attributes are the default way to map fields in PHPJson. This library allows for the use of a JSON configuration file to be passed to the serializer as an alternative method. 


## Documentation

* [Usage](#Usage)
* [Installation](#installation)
* [Dependencies](#dependencies)

## Whats required to Serialize/UnSerialize

1. add `JsonSerializable` to the class.
2. add `JsonProperty` attributes to properties, methods or at the class level.
3. Run the serializer.


## Using the Serializer

```php
echo Json::serialize($user);

// Or
$serializer = new JsonSerializer();
echo $serializer->serialize($user);
```


## Quick Example
```php
class User implements JsonSerializable
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

## Override property names
You can override the property or method names by supplying your own.
```php
class User implements JsonSerializable
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

## Example using methods
```php
class User implements JsonSerializable
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

## Deserialize (Object Hydration)
This feature is currently in development gives the ability to deserialize back to an object.
As long as the class you want to instantiate implements JsonSerializable interface, and the JSON property is mapped to the PHP property, you can instantiate any class from any JSON.

By default Json will only serialize values, if you want to deserialize (HYDRATE) the Json back to an object you need to pass `deserialize: true` in the attribute.

```json
{
    "name": "foo",
    "phoneNumbers": [
      "123456"
    ]
}
```
```php
$originalClass = new OriginalClass();

$json = Json::serialize($origalClass);
$object = Json::deserialize($json, NewClassType::class);
```
The JsonProperty attribute has additional arguments to handle
deserialization targets.

```php
class NewClassType implements JsonSerializable
{
    // Only this property will be loaded from JSON.
    #[JsonProperty(deserialize: true)]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;    
   
}
```
The `NewClassType` will instantiate the object without `$phoneNumbers` being set.


## Promoted Constructor property attributes
Json allows for attributes on the promoted properties.

```php
class MyClass implements JsonSerializable
{
    public function __construct(
        #[JsonProperty('child', deserialize: true)]
        private string $childProperty,
    ){
    }
}
```


## Object Nesting Serialization.

Given the below classes, Json will serialize them into a single Json object with class nesting as required.

Take the below code for example:

```php
$sut = new ParentClass();
$sut->name = 'fu';
$sut->someChild = new ChildClass("bar");
```

```php
class ParentClass implements JsonSerializable
{
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty('child')]
    public ChildClass $someChild;
}

class ChildClass implements JsonSerializable
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

<a name="installation"></a>
## Installation
`NOTE`: Not available via composer just yet.
Via Composer. Run the following command from your project's root.

```
composer require s-mcdonald/json
```

<a name="dependencies"></a>
## Dependencies

*  Php 8.2

## License

Json is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).


## 🙌 Contribute
Want to contribute? Check out the issues section to get started.
