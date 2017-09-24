<?php

namespace Money;


use Currency\Currency;
use Money\Exception\MoneyException;

class ExchangeException extends MoneyException
{

    public static function createFromCurrencies(Currency $baseCurrency, Currency $counterCurrency)
    {
        $message = sprintf(
            'Cannot exchange a currency pair: %s/%s',
            $baseCurrency->getCode(),
            $counterCurrency->getCode()
        );

        return new self($message);
    }
}
