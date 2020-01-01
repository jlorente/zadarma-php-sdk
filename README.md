Zadarma PHP SDK
===============
A PHP package to access the [Zadarma API](https://zadarma.com/en/support/api/) by a comprehensive way.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

With Composer installed, you can then install the extension using the following commands:

```bash
$ php composer.phar require jlorente/zadarma-php-sdk
```

or add 

```json
...
    "require": {
        "jlorente/zadarma-php-sdk": "*"
    }
```

to the ```require``` section of your `composer.json` file.

## Configuration

You can set the api keys as environment variables or add them later on Zadarma 
class instantiation.

The name of the environment variables are ZADARMA_API_KEY and ZADARMA_API_SECRET.

## Usage

Endpoints calls must done through the Zadarma class.

If you haven't set the environment variables previously, remember to provide 
them on instantiation.

```php
$zadarma = new \Jlorente\Zadarma\Zadarma($apiKey, $apiSecret);
$zadarma->api()->getBalance();
```

All the API methods are well documented in the Jlorente\Zadarma\Api class.

## License 
Copyright &copy; 2019 José Lorente Martín <jose.lorente.martin@gmail.com>.

Licensed under the BSD 3-Clause License. See LICENSE.txt for details.
