# Json (A PHP JSON Library)
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Json)
[![Source](https://img.shields.io/badge/license-MIT-gold.svg)](https://github.com/s-mcdonald/Json)
![PHP Compatibility](https://img.shields.io/badge/php-%3E%3D8.2.0-blue)
![Build Status](https://img.shields.io/github/workflow/status/s-mcdonald/json/Tests)

_A modern PHP JSON Object Serialization Library Using Attributes._

This project library aims to aid in Serializing PHP Objects/Classes using attributes.
We use attributes so we can enforce rules, and have clear guide, control and 
visibility as to which values get serialized.


## Documentation

* [Usage](#Usage)
* [Installation](#installation)
* [Dependencies](#dependencies)


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
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;    
    
    private int $creditCard;
}
```
```json
{
    "userName": "Foo",
    "phoneNumbers": [
        "044455444",
        "244755465"
    ]
}
```

## Whats required to Serialize/UnSerialize

1. add `JsonSerializable` to the class.
2. add `JsonProperty` attributes to properties, methods or class level.
3. Run the serializer.

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

## Deserialize back to PHP Object (HYDRATION)
This feature is currently in development gives the ability to deserialize back to an object.
As long as the class you want to instantiate implements JsonSerializable interface, and the JSON property is mapped to the PHP property, you can instantiate any class from any JSON.

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
    #[JsonProperty('userName', deserialize: true)]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;    
   
}
```

By default Json will only serialize objects, to deserialize the Json back to
an object you need to pass `deserialize: true` in the attribute.


## Promoted Constructor property attributes
Json allows for attributes on the promoted properties.

```php
class MyClass implements JsonSerializable
{
    public function __construct(
        #[JsonProperty('childProp1', deserialize: true)]
        private string $childProperty1,
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
