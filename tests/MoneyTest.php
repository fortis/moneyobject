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

    public function testEquals()
    {
        $money = Money::create(11.50, CurrencyCode::USD);
        $other = Money::USD(11.5);
        $this->assertTrue($money->equals($other));
    }

    public function testJsonSerialize()
    {
        $money = Money::USD(11.5);
        $json = json_encode($money);
        $data = json_decode($json);
        $this->assertTrue(Money::create($data->amount, $data->currency)->equals($money));
    }

    public function testZero()
    {
        $money = Money::zero(CurrencyCode::USD);
        $this->assertEquals(0, $money->getAmount()->toFloat());
    }

    public function testAbs()
    {
        $money = Money::USD(-100);
        $this->assertEquals(100, $money->abs()->getAmount()->toFloat());
    }

    public function testNegate()
    {
        $money = Money::USD(100);
        $this->assertEquals(-100, $money->negate()->getAmount()->toFloat());
    }
}
