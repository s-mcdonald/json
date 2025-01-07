# PHPJson
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Json)
![License](https://img.shields.io/github/license/s-mcdonald/phpjson)
![PHP Compatibility](https://img.shields.io/badge/php-%3E%3D8.2.0-blue)

![Master Build Status](https://img.shields.io/github/actions/workflow/status/s-mcdonald/phpjson/php.yml?branch=master&label=master)
![Develop Build Status](https://img.shields.io/github/actions/workflow/status/s-mcdonald/phpjson/php.yml?branch=develop&label=develop)

[![Coverage Status](https://coveralls.io/repos/github/s-mcdonald/PHPJson/badge.svg?branch=develop)](https://coveralls.io/github/s-mcdonald/PHPJson?branch=develop)

_A Fast and Lightweight PHP JSON Object Serialization Library._

## 💖 Support This Project
This project is supported by your donations! Click the **[Sponsor](https://github.com/sponsors/s-mcdonald)** link to display funding options.

___

`PHPJson` is a JSON library that provides tools to work with JSON files ans structures.
Its primary feature is the ability to serialize PHP objects into JSON and deserialize (hydrate) JSON back to PHP objects. 

Other features include
- Encode
- Decode
- Minify (uglify)
- Prettify
- Json Builder
- Serialization (including Enums)
- Hydration
- Validation

## Project Objectives
1. Make working with Json structures super simple!
2. Provide a Fast way to implement basic serialization with little to no config (using Attributes)
3. Provide a more Powerful way to implement serialization using Traits and custom Normalizers.
4. Provide a convenient way to perform common tasks with less duplication in your business logic.


## Contents

* [Usage](#Usage)
  * [Serialization](#serialization)
    * [Basic usage](#quick-usage)
    * [Override Json Properties](#override-json-properties)
    * [Nested Structures](#nested-structures)
    * [Serializing Enums](#serializing-enums)
  * [Hydration/Deserialization](#deserialize-aka-object-hydration)
    * [Basic Hydration](#basic-hydration)
    * [Hydrate with setters](#hydrate-to-setters)
  * [JsonBuilder](#jsonbuilder)
    * [Basic Builder](#jsonbuilder-basics)
    * [Objects and Arrays](#jsonbuilder-objects-and-arrays)
  * [Json Formatting](#json-formatting)
    * [Prettify](#prettify--Uglify)
* [Installation](#installation)
* [Dependencies](#dependencies)
* [License](#license)
* [Contribute](#contribute)

# Usage

### Serialization

#### Quick usage
The fasted way to serialize your class into Json is to use the JsonProperty attribute.
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
You can override the property name by supplying your own. This also works in reverse for hydration.

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
You can also serialize values from your getter methods. The method can be public protected or private and `PHPJson` will extract the value from it.
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

#### Nested Structures

You can also have complex nested objects, and `PHPJson` will produce valid json with nested values accordingly.


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

`PHPJson` also supports serializing all forms of enums, pure and backed.

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

### Deserialize aka Object Hydration
#### Basic Hydration
For simple Hydration, you do not need to implement any attributes or have a mapping for properties, as long as the Class you use has the same properties within your json, `PHPJson` will hydrate your class or entity.


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

#### Hydrate to setters
If your class requires processing or passing by a setter method, you can hydrate to a setter given that it adheres to;
* Single required argument
* JsonProperty attribute is used to denote the hydration.
* Required param is of matching type

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
        ->addProperty('title', "Perfume Oil")
        ->addProperty('rating', 4.26)
        ->addProperty('stock', 65);

echo $builder;
```
```json
{
    "id": 11,
    "title": "Perfume Oil",
    "rating": 4.26,
    "stock": 65
}
```

#### JsonBuilder Objects and Arrays

```php

$builder = Json::createJsonBuilder()
        ->addProperty('id', 11)
        ->addProperty('title', "Perfume Oil")
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
