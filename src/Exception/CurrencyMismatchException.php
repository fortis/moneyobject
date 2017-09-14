<?php

namespace Money\Exception;

use Currency\Currency;

class CurrencyMismatchException extends MoneyException
{
    /**
     * @param Currency $expected
     * @param Currency $actual
     *
     * @return CurrencyMismatchException
     */
    public static function currencyMismatch(Currency $expected, Currency $actual)
    {
        return new self(
            sprintf(
                'Currency mismatch: expected %s, got %s',
                $expected->getCode(),
                $actual->getCode()
            )
        );
    }
}
