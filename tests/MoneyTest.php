<?php

namespace Money\Tests;

use Brick\Math\Tests\AbstractTestCase;
use Currency\Currency;
use Currency\CurrencyCode;
use Money\Money;

/**
 * Unit tests for class Money.
 */
class MoneyTest extends AbstractTestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf(Money::class, Money::create(11.50, 'USD', 2));
        $this->assertInstanceOf(Money::class, Money::create(4, CurrencyCode::USD));
        $this->assertInstanceOf(Currency::class, Money::create(11.50, 'USD', 2)->getCurrency());
        $this->assertInstanceOf(Money::class, Money::USD(2));
    }

    public function testAmountToFloat()
    {
        $money = Money::create(11.50, 'USD', 2);
        $this->assertEquals(11.50, $money->getAmount()->toFloat());
    }

    public function testStaticFactoryMethod()
    {
        $money = Money::EUR(5.5);
        $this->assertEquals(5.5, $money->getAmount()->toFloat());
        $this->assertEquals('EUR', $money->getCurrency());
    }

    public function testMultiply()
    {
        $money = Money::create(11.50, CurrencyCode::USD, 2);
        $this->assertEquals(23, $money->multiply(2)->getAmount()->toFloat());

        $money = Money::create(11.50, CurrencyCode::USD);
        $this->assertEquals(23, $money->multiply(Money::USD(2))->getAmount()->toFloat());
    }

    public function testDivide()
    {
        $money = Money::create(10, CurrencyCode::USD);
        $this->assertEquals(5, $money->divide(Money::USD(2))->getAmount()->toFloat());
    }

    public function testPlus()
    {
        $money = Money::create(11.50, CurrencyCode::USD);
        $this->assertEquals(14, $money->plus(Money::USD(2.5))->getAmount()->toFloat());
    }

    public function testMinus()
    {
        $money = Money::create(11.50, CurrencyCode::USD);
        $this->assertEquals(11, $money->minus(Money::USD(0.5))->getAmount()->toFloat());
    }

    public function testIsEqualTo()
    {
    }
}
