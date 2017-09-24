<?php

namespace Money;

use Currency\Currency;

class Converter
{
    /**
     * @var SwapExchange
     */
    private $exchange;

    /**
     * @param SwapExchange $exchange
     */
    public function __construct(SwapExchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * @param Money    $money
     * @param Currency $counterCurrency
     *
     * @return Money
     * @throws \Money\ExchangeException
     * @throws \InvalidArgumentException
     * @throws \Brick\Math\Exception\ArithmeticException
     */
    public function convert(Money $money, Currency $counterCurrency)
    {
        $baseCurrency = $money->getCurrency();
        $baseCurrencySubunit = $baseCurrency->getMinorUnit();
        $counterCurrencySubunit = $counterCurrency->getMinorUnit();
        $subunitDifference = $baseCurrencySubunit - $counterCurrencySubunit;

        $ratio = $this->exchange->quote($baseCurrency, $counterCurrency)->getValue();
        $ratio /= 10 ** $subunitDifference;
        $counterValue = $money->getAmount()->multipliedBy($ratio);

        return new Money($counterValue, $counterCurrency);
    }
}
