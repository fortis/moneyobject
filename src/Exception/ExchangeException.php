<?php

namespace Money;


use Money\Exception\MoneyException;

class ExchangeException extends MoneyException
{

    public static function createFromCurrencies($baseCurrency, $counterCurrency)
    {
        $message = sprintf(
            'Cannot exchange a currency pair: %s/%s',
            $baseCurrency->getCode(),
            $counterCurrency->getCode()
        );

        return new self($message);
    }
}
