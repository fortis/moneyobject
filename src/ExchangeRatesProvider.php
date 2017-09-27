<?php

namespace Money;

/**
 * Contract for exchange rate providers.
 *
 * @package Money
 */
interface ExchangeRatesProvider
{
    /**
     * Quotes a currency pair.
     * @param $pair
     * @return mixed
     */
    public function quote($pair);
}
