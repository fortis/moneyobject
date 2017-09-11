<?php

namespace Money;

use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Brick\Math\Exception\ArithmeticException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Currency\Currency;

/**
 * Money class.
 *
 * Most popular currency codes for autocomplete.
 * @method static Money USD($amount)
 * @method static Money EUR($amount)
 * @method static Money RUB($amount)
 * @method static Money JPY($amount)
 * @method static Money GBP($amount)
 * @method static Money CHF($amount)
 * @method static Money CAD($amount)
 * @method static Money AUD($amount)
 * @method static Money ZAR($amount)
 */
class Money implements \JsonSerializable
{
    /** @var BigDecimal */
    private $amount;

    /** @var Currency */
    private $currency;

    /**
     * @param BigNumber|number|string $amount       The amount.
     * @param string                  $currencyCode The currency code.
     * @param null                    $customMinorUnit
     * @param int                     $rounding
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function __construct($amount, $currencyCode, $customMinorUnit = null, $rounding = RoundingMode::UNNECESSARY)
    {
        $this->currency = Currency::create($currencyCode, $customMinorUnit);
        $this->amount = BigDecimal::of($amount)->toScale($this->currency->getMinorUnit(), $rounding);
    }

    /**
     * @param string $amount
     * @param string $currencyCode
     * @param null   $customMinorUnit
     * @param int    $rounding
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public static function create(
        $amount,
        $currencyCode,
        $customMinorUnit = null,
        $rounding = RoundingMode::UNNECESSARY
    ) {
        return new self($amount, $currencyCode, $customMinorUnit, $rounding);
    }

    /**
     * Convenience factory method for a Money object.
     *
     * <code>
     * $fiveDollar = Money::USD(5);
     * </code>
     *
     * @param $currencyCode
     * @param $arguments
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public static function __callStatic($currencyCode, $arguments)
    {
        $amount = isset($arguments[0]) ? $arguments[0] : 0;

        return new self($amount, $currencyCode);
    }

    /**
     * Returns a Money with zero value, in the given Currency.
     *
     * @param Currency|string $currencyCode    A currency currency code.
     * @param int|null        $customMinorUnit Custom currency minor unit, or null to use the default.
     *
     * @return Money
     * @throws RoundingNecessaryException
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public static function zero($currencyCode, $customMinorUnit = null)
    {
        $currencyCode = Currency::create($currencyCode, $customMinorUnit);
        $amount = BigDecimal::zero()->toScale($currencyCode->getMinorUnit());

        return new self($amount, $currencyCode);
    }

    /**
     * Returns the amount of this Money, as a BigDecimal.
     *
     * @return BigDecimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the Currency of this Money.
     *
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Returns a Money whose value is the absolute value of this Money.
     *
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function abs()
    {
        return new self($this->amount->abs(), $this->currency->getCode());
    }

    /**
     * Returns a Money whose value is the negated value of this Money.
     *
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function negate()
    {
        return new self($this->amount->negated(), $this->currency->getCode());
    }

    /**
     * Returns whether this Money has zero value.
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->amount->isZero();
    }

    /**
     * Returns whether this Money has a negative value.
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->amount->isNegative();
    }

    /**
     * Returns whether this Money has a negative or zero value.
     *
     * @return bool
     */
    public function isNegativeOrZero()
    {
        return $this->amount->isNegativeOrZero();
    }

    /**
     * Returns whether this Money has a positive value.
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->amount->isPositive();
    }

    /**
     * Returns whether this Money has a positive or zero value.
     *
     * @return bool
     */
    public function isPositiveOrZero()
    {
        return $this->amount->isPositiveOrZero();
    }

    /**
     * Returns a new Money object that represents
     * the multiplied value by the given factor.
     *
     * @param $that
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function multiply($that)
    {
        $multiplier = $that instanceof Money ? $that->getAmount() : $that;
        $amount = $this->amount->multipliedBy($multiplier);

        return new self($amount, $this->currency->getCode());
    }

    /**
     * Returns a new Money object that represents
     * the divided value by the given factor.
     *
     * @param $that
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function divide($that)
    {
        $divisor = $that instanceof Money ? $that->getAmount() : $that;
        $amount = $this->amount->dividedBy($divisor);

        return new self($amount, $this->currency->getCode());
    }

    /**
     * Returns a new Money object that represents
     * the sum of this and an other Money object.
     *
     * @param $that
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function plus($that)
    {
        $addend = $that instanceof Money ? $that->getAmount() : $that;
        $amount = $this->amount->plus($addend);

        return new self($amount, $this->currency->getCode());
    }

    /**
     * Returns a new Money object that represents
     * the difference of this and an other Money object.
     *
     * @param $that
     * @return Money
     * @throws \InvalidArgumentException
     * @throws ArithmeticException
     */
    public function minus($that)
    {
        $subtrahend = $that instanceof Money ? $that->getAmount() : $that;
        $amount = $this->amount->minus($subtrahend);

        return new self($amount, $this->currency->getCode());
    }

    /**
     * Formats this Money with the given NumberFormatter.
     *
     * Note that NumberFormatter internally represents values using floating point arithmetic,
     * so discrepancies can appear when formatting very large monetary values.
     *
     * @param \NumberFormatter $formatter
     *
     * @return string
     */
    public function formatWith(\NumberFormatter $formatter)
    {
        return $formatter->formatCurrency(
            (string)$this->amount,
            (string)$this->currency
        );
    }

    /**
     * Formats this Money to the given locale.
     *
     * Note that this method uses NumberFormatter, which internally represents values using floating point arithmetic,
     * so discrepancies can appear when formatting very large monetary values.
     *
     * @param string $locale
     *
     * @return string
     */
    public function formatTo($locale)
    {
        return $this->formatWith(new \NumberFormatter($locale, \NumberFormatter::CURRENCY));
    }

    /**
     * Returns a non-localized string representation of this Money, e.g. "EUR 23.00".
     *
     * @return string
     */
    public function __toString()
    {
        return $this->currency.' '.$this->amount;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'amount' => (string)$this->amount,
            'currency' => (string)$this->currency,
        ];
    }
}
