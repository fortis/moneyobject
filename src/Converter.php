<?php

namespace Money;

use Currency\Currency;
use Money\Exception\ExchangeException;

class Converter
{
    /** @var ExchangeRatesProvider */
    private $ratesProvider;

    /**
     * Converter constructor.
     *
     * @param \Money\ExchangeRatesProvider $exchanger
     */
    public function __construct(ExchangeRatesProvider $exchanger)
    {
        $this->ratesProvider = $exchanger;
    }

    /**
     * @param Money $money
     * @param Currency $counterCurrency
     *
     * @return Money
     * @throws ExchangeException
     */
    public function convert(Money $money, Currency $counterCurrency)
    {
        $baseCurrency = $money->getCurrency();
        $baseCurrencySubunit = $baseCurrency->getMinorUnit();
        $counterCurrencySubunit = $counterCurrency->getMinorUnit();
        $subunitDifference = $baseCurrencySubunit - $counterCurrencySubunit;
        $pair = $baseCurrency->getCode().'/'.$counterCurrency->getCode();

        try {
            $ratio = $this->ratesProvider->quote($pair);
        } catch (\Exception $e) {
            throw ExchangeException::createFromCurrencies($baseCurrency, $counterCurrency);
        }

        $ratio /= 10 ** $subunitDifference;
        $counterValue = $money->getAmount()->multipliedBy($ratio);

        return new Money($counterValue, $counterCurrency);
    }
}
