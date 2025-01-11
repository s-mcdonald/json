# PHPJson
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Json)
![License](https://img.shields.io/github/license/s-mcdonald/phpjson)
![PHP Compatibility](https://img.shields.io/badge/php-%3E%3D8.2.0-blue)

![Master Build Status](https://img.shields.io/github/actions/workflow/status/s-mcdonald/phpjson/php.yml?branch=master&label=master)
![Develop Build Status](https://img.shields.io/github/actions/workflow/status/s-mcdonald/phpjson/php.yml?branch=develop&label=develop)

[![Coverage Status](https://coveralls.io/repos/github/s-mcdonald/PHPJson/badge.svg?branch=develop)](https://coveralls.io/github/s-mcdonald/PHPJson?branch=develop)

_A Fast and Lightweight PHP JSON Object Serialization Library._

## 💖 Support This Project
`PHPJson` is supported by your donations! Click the **[Sponsor](https://github.com/sponsors/s-mcdonald)** link to display funding options.

___

`PHPJson` is a library that provides tools to work with JSON files and structures for PHP.
Its primary feature is the ability to quickly and easily serialize PHP objects into JSON and deserialize (hydrate) JSON back to PHP objects. 

Other features include
- Encode
- Decode
- Minify (uglify)
- Prettify
- Json Builder
- Serialization (including Enums)
- Serialization value casting
- Hydration
- Validation

## Project Objectives
1. Simplify working with JSON structures.
2. Enable fast and minimal-configuration serialization using attributes.
3. Provide advanced serialization capabilities through traits and custom normalizers.
4. Reduce duplication in business logic by streamlining common tasks.


## Contents

* [Usage](#Usage)
  * [Serialization](#serialization)
    * [Basic usage](#quick-usage)
    * [Override Json Properties](#override-json-properties)
    * [Nested Structures](#nested-structures)
    * [Serializing Enums](#serializing-enums)
    * [Casting values](#casting-values)
  * [Hydration/Deserialization](#deserialize-aka-object-hydration)
    * [Basic Hydration](#basic-hydration)
    * [Hydration with Setter Methods](#hydration-with-setter-methods)
  * [JsonBuilder](#jsonbuilder)
    * [Basic Builder](#jsonbuilder-basics)
    * [Objects and Arrays](#jsonbuilder-objects-and-arrays)
  * [Json Formatting](#json-formatting)
    * [Prettify](#prettify--Uglify)
  * [Json Validate](#json-validate)
* [Installation](#installation)
* [Dependencies](#dependencies)
* [License](#license)
* [Contribute](#contribute)

# Usage

### Serialization

#### Quick usage
The fastest way to serialize a class into JSON is by using the `JsonProperty` attribute.
```php
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
```php
$serializer = new JsonSerializer();
echo $serializer->serialize($user);
```

Or you can use the Json facade
```php
echo Json::serialize($user); // outputs json
```

#### Override Json Properties
You can customize property names in the JSON output by specifying your own names. This also applies when hydrating objects from JSON.

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

#### Serialize from methods
You can serialize values from getter methods, regardless of whether the method is public, protected, or private. `PHPJson` will automatically extract the value.

```php
class User
{
    #[JsonProperty]
    public function authenticator(): string
    {
        return $this->authenticator;
    }
    
    #[JsonProperty('creditCardNumber')]
    public function getCreditCard(): int
    {
        return $this->creditCard;
    }
}
```
```json
{
    "authenticator": "MasterCard",
    "creditCardNumber": "55044455444677"
}
```

#### Nested Structures

`PHPJson` allows you to work seamlessly with complex, nested objects. Nested classes and their properties are serialized into valid JSON structures, matching the relationships between objects.


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
```php
$sut = new ParentClass();
$sut->name = 'fu';
$sut->someChild = new ChildClass("bar");
```
```json
{
    "userName": "fu",
    "child": {
        "childProp": "bar"
    }
}
```

#### Serialization Using a trait
The above method utilizes the JsonProperty to serialize any object. This is by far the easiest and fasted way to convert your objects into Json. However, this will have some limitations. To overcome this, we have included two Facets called `SerializesWithMapping` and `SerializesToJson`.  With these Facets you can customize the Serialization and export vastly more complex objects.


#### Serializing Enums

`PHPJson` supports the serialization of both pure and backed enums.

Pure Enum
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

Backed Enum
```php
enum Status: int
{
    case Enabled = 10;
    case Disabled = 20;
}

echo Json::serialize(Status::Enabled);
```
```json
{
    "Status": 10
}
```

#### Casting Values

When serializing a PHP object to JSON, you might need to cast specific property values into different types for the JSON output. You can achieve this by using the `JsonProperty` attribute to specify the desired type using a `JsonType`, such as `StringType` or `IntegerType`.

```php
class 
{
    #[JsonProperty(type: new StringType())]
    public float $myNumber = 123.456;
    
    #[JsonProperty(type: new IntegerType())]
    public float $myNumber2 = 123.456;
}
```
```json
{
  "myNumber": "123.456",
  "myNumber2": 123
}
```

Available types are;
  * StringType
  * ArrayType,
  * BooleanType,
  * DoubleType,
  * IntegerType,
  * NullType,
  * ObjectType



### Deserialize aka Object Hydration
#### Basic Hydration
With `PHPJson`, basic object hydration is straightforward. If your class properties match the structure and property names in your JSON, no additional attributes or mappings are required. The library will automatically map the JSON data to your class or entity.


```php
class MyUser 
{
    public string $name;
    public int $age;
    public bool $isActive;
}
```
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

#### Hydration with Setter Methods

If your class relies on setters for processing or assigning values, `PHPJson` can hydrate using setter methods, provided these conditions are met:

- The setter accepts exactly one required argument.
- The `JsonProperty` attribute is used to specify the property for hydration.
- The argument type matches the data type in the JSON.
- 
```php
class MyUser 
{
    #[JsonProperty('name')]
    public string $userName;

    public int $age;
    public bool $isActive;
    
    #[JsonProperty('name')]
    public function setUserName(string $value): void
    {
        $this->userName = 'foo: ' . $value;
    }
}
```
Notice how the JsonProperty is used twice here, for hydration setter methods will be the preferred hydration point, since, `setUserName` can not be used for serialization, the property `$userName` will be used for this.

### JsonBuilder
Fluently create Json objects using PHP.

#### JsonBuilder Basics
```php

$builder = Json::createJsonBuilder()
        ->addProperty('id', 11)
        ->addProperty('title', "Apple iOS 15")
        ->addProperty('rating', 4.26)
        ->addProperty('stock', 65);

echo $builder;
```
```json
{
    "id": 11,
    "title": "Apple iOS 15",
    "rating": 4.26,
    "stock": 65
}
```

#### JsonBuilder Objects and Arrays

```php

$builder = Json::createJsonBuilder()
        ->addProperty('id', 11)
        ->addProperty('title', "Apple iOS 15")
        ->addProperty('rating', 4.26)
        ->addProperty('stock', 65);
        
        
echo $builder->addProperty(
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
    "title": "Apple iOS 15",
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

### Json Formatting

#### Prettify & Uglify
Prettify or Uglify(minify) your json values

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

#### Json Validate
PHP 8.3 onwards has the json_validate function. This library duplicates this behaviour so it can bve used in PHP 8.2

```php
Json::validate($json): bool

// or
json_validate($json): bool 
```

# Reference

## Installation
Install this package via composer, or simply fork/clone the repo.
```bash
composer require s-mcdonald/phpjson
```

## Dependencies

*  None

## PHP Versions

*  PHP 8.2, 8.3, 8.4

## License

Json is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).

## Contribute
🙌 Want to contribute? 

Check out the issues section to get started.

[![Sponsor](https://img.shields.io/badge/Sponsor-❤-ff69b4)](https://github.com/sponsors/s-mcdonald)
