<?php

namespace Money;

use Currency\Currency;
use Exchanger\CurrencyPair;
use Swap\Swap;

class SwapExchange
{
    /**
     * @var Swap
     */
    private $swap;

    /**
     * @param Swap $swap
     */
    public function __construct(Swap $swap)
    {
        $this->swap = $swap;
    }

    /**
     * {@inheritdoc}
     * @throws \Money\ExchangeException
     */
    public function quote(Currency $baseCurrency, Currency $quoteCurrency)
    {
        try {
            $pair = new CurrencyPair($baseCurrency, $quoteCurrency);

            return $this->swap->latest($pair);
        } catch (\Exception $e) {
            throw ExchangeException::createFromCurrencies($baseCurrency, $quoteCurrency);
        }
    }
}