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

    // serialization of value comes from the method
    // below. For deserialization, this value
    // will not be mapped back.       
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
