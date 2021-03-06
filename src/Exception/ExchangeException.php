<?php

namespace Money\Exception;

use Currency\Currency;

class ExchangeException extends MoneyException
{

    public static function createFromCurrencies(Currency $baseCurrency, Currency $counterCurrency)
    {
        return new self(
            sprintf(
                'Cannot exchange a currency pair: %s/%s',
                $baseCurrency->getCode(),
                $counterCurrency->getCode()
            )
        );
    }
}
