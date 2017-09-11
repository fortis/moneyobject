# moneyobject

[![Travis](https://img.shields.io/travis/fortis/moneyobject.svg?branch=master)](https://travis-ci.org/fortis/moneyobject)
[![Coveralls](https://img.shields.io/coveralls/fortis/moneyobject/master.svg)](https://coveralls.io/github/fortis/moneyobject?branch=master)
[![Packagist](https://img.shields.io/packagist/l/fortis/moneyobject.svg)](https://packagist.org/packages/fortis/moneyobject)

A PHP library providing immutable Money value object.

## Install

Install directly from command line using Composer
``` bash
composer require fortis/moneyobject
```

## Use

``` php
// Create Currency instance.
$money = Money::USD(100);                       // 100 USD. Short syntax with autocomplete.
$money = new Money(100, CurrencyCode::USD);     // 100 USD  
$money = Money::create(100, CurrencyCode::USD); // 100 USD

// Currency code validation.
$money = new Money(100, 'USF');   // throws InvalidCurrencyException

// Get currency.
$money->getCurrency()->getCode(); // USD

// Get amount.
$money->getAmount()->toFloat();   // 100

// Multiply.
$money->multiply(2)->getAmount()->toFloat(); // 200

// Divide.
$money->divide(Money::USD(2))->getAmount()->toFloat(); // 50

// Plus.
$money->plus(Money::USD(2.5))->getAmount()->toFloat(); // 102.5

// Minus.
$money->minus(Money::USD(0.5))->getAmount()->toFloat(); // 99.5
```

## License

moneyobject is licensed under the MIT license.
