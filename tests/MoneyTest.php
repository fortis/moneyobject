<?php

namespace Money\Tests;

use Brick\Math\RoundingMode;
use Currency\Currency;
use Currency\CurrencyCode;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class Money.
 */
class MoneyTest extends TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate($expectedInstance, $resultInstance)
    {
        $this->assertInstanceOf($expectedInstance, $resultInstance);
    }

    public function createDataProvider()
    {
        return [
            [Money::class, Money::create(11.50, 'USD', 2)],
            [Money::class, Money::create(4, CurrencyCode::USD)],
            [Currency::class, Money::create(11.50, 'USD', 2)->getCurrency()],
            [Money::class, Money::USD(2)],  
        ];
    }

    public function testAmountToFloat()
    {
        $money = Money::create(11.50, CurrencyCode::USD, 2);
        $this->assertEquals(11.50, $money->getAmount()->toFloat());
    }

    public function testStaticFactoryMethod()
    {
        $money = Money::EUR(5.5);
        $this->assertEquals(5.5, $money->getAmount()->toFloat());
        $this->assertEquals(CurrencyCode::EUR, $money->getCurrency());
    }

    public function testMultiply()
    {
        $money = Money::create(11.50, CurrencyCode::USD, 2);
        $this->assertEquals(23, $money->multiply(2)->getAmount()->toFloat());

        $money = Money::create(11.50, CurrencyCode::USD);
        $this->assertEquals(23, $money->multiply(Money::USD(2))->getAmount()->toFloat());

        $money = Money::create(11.50, CurrencyCode::USD);
        $this->assertEquals(
            26,
            $money->multiply(2.2)
                ->getAmount()
                ->toScale(0, RoundingMode::CEILING)
                ->toInt()
        );
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

    public function testIsZero()
    {
        $money = Money::zero(CurrencyCode::USD);
        $this->assertTrue($money->isZero());
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

    public function testIsPositive()
    {
        $money = Money::USD(100);
        $this->assertTrue($money->isPositive());
    }

    public function testIsPositiveOrZero()
    {
        $money = Money::USD(100);
        $this->assertTrue($money->isPositiveOrZero());
    }

    public function testIsNegative()
    {
        $money = Money::USD(100);
        $money->negate();
        $this->assertFalse($money->isNegative());
    }

    public function testIsNegativeOrZero()
    {
        $money = Money::USD(100);
        $money->negate();
        $this->assertFalse($money->isNegativeOrZero());
    }

    public function testLessThan()
    {
        $money = Money::USD(100);
        $this->assertFalse($money->lessThan(10));
    }

    public function testLessThanOrEqual()
    {
        $money = Money::USD(100);
        $this->assertTrue($money->lessThanOrEqual(100));
    }

    public function testGreaterThan()
    {
        $money = Money::USD(100);
        $this->assertFalse($money->greaterThan(1000));
    }

    public function testGreaterThanOrEqual()
    {
        $money = Money::USD(100);
        $this->assertTrue($money->greaterThanOrEqual(100));
    }

    public function testFormatWith()
    {
        $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $money = Money::USD(100);
        $this->assertEquals('one hundred', $money->formatWith($numberFormatter));
    }

    public function testFormatTo()
    {
        $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $money = Money::USD(100);
        $this->assertEquals('$100.00', $money->formatTo('en'));
    }

    public function testToString()
    {
        $money = Money::USD(100);
        $this->assertEquals('USD 100.00', (string)$money);
    }

    public function testFloatRounding()
    {
        // Rounding problem.
        $this->assertTrue((36 - 35.99) !== 0.01);

        // Solution.
        $first = Money::USD(36);
        $second = Money::USD(35.99);
        $this->assertEquals(0.01, $first->minus($second)->getAmount()->toFloat());
    }

    /**
     * @dataProvider roundingDataProvider
     */
    public function testRoundingIsNecessary($expectedInstance, $resultInstance)
    {
        $this->assertInstanceOf($expectedInstance, $resultInstance);
    }

    public function roundingDataProvider()
    {
        return [
            [Money::class, Money::RUB(204.08037037037 / 0.80, null, RoundingMode::FLOOR)],
            [Money::class, Money::RUB(6146.68)->divide(20, RoundingMode::FLOOR)],
            [Money::class, Money::RUB(6146.68)->plus(20)],
        ];
    }
}
