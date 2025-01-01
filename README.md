# Json (A PHP JSON Library)
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Json)
[![Source](https://img.shields.io/badge/license-MIT-gold.svg)](https://github.com/s-mcdonald/Json)

Serialize a class using attributes.

## Documentation

* [Usage](#Usage)
* [Installation](#installation)
* [Dependencies](#dependencies)

## Usage
### Apply Attributes to Serialize/UnSerialize

1. add `JsonSerializable` to the class.
2. add `JsonProperty` attributes to properties.

By default Json will only serialize data/objects, to deserialize the Json back to
an object you need to pass `deserialize: true` in the attribute

```php
class User implements JsonSerializable
{
    #[JsonProperty('userName', deserialize: true)]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;    
    
    private int $creditCard;
   
    #[JsonProperty('userAddress', deserialize: true)]
    private string $address;

    #[JsonProperty('creditCard')]
    public function getCreditCard(): int
    {
        return $this->creditCard;
    }
}
```

### Serialize using the Json `static` or the `JsonSerializer`
You can use the static helper
```php
echo Json::serialize($user);

// Or
$serializer = new JsonSerializer();
echo $serializer->serialize($user);
```

Which will produce the following.

```php
{
    "userName": "Foo",
    "phoneNumbers": [
        "044455444",
        "244755465"
    ],
    "creditCard": 54454.5,
    "userAddress": "123 Fake St Arizona."
}
```

# Promoted Constructor property attributes
Json allows for attributes on the promoted properties.

```php

class MyClass implements JsonSerializable
{
    public function __construct(
        #[JsonProperty('childProp1')]
        private string $childProperty1,
    ){
    }
}
```


# Nesting and Sub Class Serialization.

Given the below classes, Json will serialize them into a single Json output with class nesting.

```php
class ParentClass implements JsonSerializable
{
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty('child')]
    public GoodChildObjectSerializable $child;
}

class ChildClass implements JsonSerializable
{
    public function __construct(
        #[JsonProperty('childProp1')]
        private string $childProperty1,
    ){
    }
}
```
If we initialize some values..

```php
$sut = new ParentClass();
$sut->name = 'foo';
$sut->child = new ChildClass("fubar");
```
And the output would be

```json
{
    "userName": "foo",
    "child": {
        "childProp1": "fubar"
    }
}
```

# Deserialize back to object
Currently in development is the ability to deserialize back to an object.
As long as the class you want to instantiate implements JsonSerializable, and the json properties are mapped to property values or constructor properties, you can instantiate any class not just the original class type that it was serialized from.

```php
$originalClass = new OriginalClass();

$json = Json::serialize($origalClass);
$object = Json::deserialize($json, NewClassType::class);
```
The JsonProperty attribute has additional arguments to handle
deserialization targets.


<a name="installation"></a>
## Installation

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
