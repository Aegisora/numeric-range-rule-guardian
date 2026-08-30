<?php

namespace Aegisora\RuleGuardians\NumericRangeRule\Tests\Unit;

use Aegisora\Guardian\Exceptions\GuardianExecutingRuleException;
use Aegisora\Guardian\Exceptions\GuardianValidationException;
use Aegisora\Guardian\Guardian;
use Aegisora\RuleContract\Exceptions\InvalidRuleContextException;
use Aegisora\RuleGuardians\NumericRangeRule\NumericRangeRuleGuardian;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;

class NumericRangeRuleGuardianTest extends TestCase
{
    private NumericRangeRuleGuardian $guardian;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guardian = new NumericRangeRuleGuardian(
            new Guardian()
        );
    }

    /**
     * @dataProvider getSuccessfullyCheckProvidedData
     */
    public function testSuccessfullyCheck(
        callable $check
    ): void {
        $this->expectNotToPerformAssertions();

        $check($this->guardian);
    }

    public static function getSuccessfullyCheckProvidedData(): array
    {
        return [
            // greater than
            'greater than - above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(4, 3),
            ],
            'greater than - value float, min int, just above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(3.0001, 3),
            ],
            'greater than - value float, min float, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(3.2, 3.1),
            ],
            'greater than - value int, min float, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(4, 3.5),
            ],
            'greater than - value string, min int, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan('4', 3),
            ],
            'greater than - value string, min string, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan('4', '3'),
            ],
            'greater than - value float string, min int, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan('3.5', 3),
            ],
            'greater than - negative boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(0, -1),
            ],
            // greater than or equal to
            'greater than or equal to - int equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3, 3),
            ],
            'greater than or equal to - value float, min float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3.1, 3.1),
            ],
            'greater than or equal to - integer float equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3.0, 3.0),
            ],
            'greater than or equal to - value int, min float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3, 3.0),
            ],
            'greater than or equal to - value float, min float, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3.2, 3.1),
            ],
            'greater than or equal to - value float, min int, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3.5, 3),
            ],
            'greater than or equal to - value string, min int, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo('3', 3),
            ],
            'greater than or equal to - value string, min string, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo('3', '3'),
            ],
            'greater than or equal to - value string, min string, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo('4', '3'),
            ],
            'greater than or equal to - value float string, min float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo('3.1', 3.1),
            ],
            'greater than or equal to - above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(4, 3),
            ],
            // less than
            'less than - below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(2, 3),
            ],
            'less than - value float, max int, just below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(2.9999, 3),
            ],
            'less than - value float, max float, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(3.0, 3.1),
            ],
            'less than - value int, max float, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(3, 3.5),
            ],
            'less than - value string, max int, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan('2', 3),
            ],
            'less than - value string, max string, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan('2', '3'),
            ],
            'less than - value float string, max int, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan('2.5', 3),
            ],
            'less than - negative value below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(-5, 3),
            ],
            // less than or equal to
            'less than or equal to - below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(2, 3),
            ],
            'less than or equal to - equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(3, 3),
            ],
            'less than or equal to - value float, max float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(3.1, 3.1),
            ],
            'less than or equal to - value float, max float, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(3.0, 3.1),
            ],
            'less than or equal to - value int, max float, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(3, 3.5),
            ],
            'less than or equal to - value string, max int, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo('3', 3),
            ],
            'less than or equal to - value string, max string, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo('3', '3'),
            ],
            'less than or equal to - value string, max string, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo('2', '3'),
            ],
            'less than or equal to - value float string, max float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo('3.1', 3.1),
            ],
            // between
            'between - equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(2, 2, 4),
            ],
            'between - inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(3, 2, 4),
            ],
            'between - equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(4, 2, 4),
            ],
            'between - value float, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(3.5, 2, 4),
            ],
            'between - value float, bounds float, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(2.5, 2.5, 4.5),
            ],
            'between - value float, bounds float, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(4.5, 2.5, 4.5),
            ],
            'between - value float, bounds float, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(3.5, 2.5, 4.5),
            ],
            'between - value string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween('3', 2, 4),
            ],
            'between - value string, bounds int, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween('2', 2, 4),
            ],
            'between - value string, bounds string, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween('3', '2', '4'),
            ],
            'between - value float string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween('3.5', 2, 4),
            ],
            'between - equal to single point range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(3, 3, 3),
            ],
            // between exclusive
            'between exclusive - inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(3, 2, 4),
            ],
            'between exclusive - value float, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(3.5, 2, 4),
            ],
            'between exclusive - value float, bounds int, just above min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(2.0001, 2, 4),
            ],
            'between exclusive - value float, bounds int, just below max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(3.9999, 2, 4),
            ],
            'between exclusive - value float, bounds float, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(3.5, 2.5, 4.5),
            ],
            'between exclusive - value int, bounds float, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(3, 2.5, 4.5),
            ],
            'between exclusive - value string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive('3', 2, 4),
            ],
            'between exclusive - value string, bounds string, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive('3', '2', '4'),
            ],
            'between exclusive - value float string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive('3.5', 2, 4),
            ],
            // between min exclusive
            'between min exclusive - inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(3, 2, 4),
            ],
            'between min exclusive - equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(4, 2, 4),
            ],
            'between min exclusive - value float, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(3.5, 2, 4),
            ],
            'between min exclusive - value float, bounds int, just above min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(2.0001, 2, 4),
            ],
            'between min exclusive - value float, bounds float, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(4.5, 2.5, 4.5),
            ],
            'between min exclusive - value int, bounds float, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(3, 2.5, 4.5),
            ],
            'between min exclusive - value string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive('3', 2, 4),
            ],
            'between min exclusive - value string, bounds int, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive('4', 2, 4),
            ],
            'between min exclusive - value string, bounds string, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive('3', '2', '4'),
            ],
            'between min exclusive - value float string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive('3.5', 2, 4),
            ],
            // between max exclusive
            'between max exclusive - equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(2, 2, 4),
            ],
            'between max exclusive - inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(3, 2, 4),
            ],
            'between max exclusive - value float, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(3.5, 2, 4),
            ],
            'between max exclusive - value float, bounds int, just below max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(3.9999, 2, 4),
            ],
            'between max exclusive - value float, bounds float, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(2.5, 2.5, 4.5),
            ],
            'between max exclusive - value int, bounds float, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(3, 2.5, 4.5),
            ],
            'between max exclusive - value string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive('3', 2, 4),
            ],
            'between max exclusive - value string, bounds int, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive('2', 2, 4),
            ],
            'between max exclusive - value string, bounds string, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive('3', '2', '4'),
            ],
            'between max exclusive - value float string, bounds int, inside range' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive('3.5', 2, 4),
            ],
        ];
    }

    /**
     * @dataProvider getFailedCheckProvidedData
     */
    public function testFailedCheck(
        callable $check,
        string $expectedExceptionClassName
    ): void {
        $this->expectException($expectedExceptionClassName);

        $check($this->guardian);
    }

    public static function getFailedCheckProvidedData(): array
    {
        return [
            // greater than
            'greater than - below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(2, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than - equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(3, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than - value float, min int, just below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(2.9999, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than - value float, min float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(3.1, 3.1),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than - value string, min int, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan('3', 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than - value string, min string, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan('3', '3'),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThan(2, 3, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // greater than or equal to
            'greater than or equal to - below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(2, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than or equal to - value float, min float, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo(3.0, 3.1),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than or equal to - value string, min int, below boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkGreaterThanOrEqualTo('2', 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'greater than or equal to - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian)
                    => $guardian->checkGreaterThanOrEqualTo(2, 3, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // less than
            'less than - equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(3, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than - above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(4, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than - value float, max int, just above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(3.0001, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than - value float, max float, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(3.1, 3.1),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than - value string, max int, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan('3', 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than - value string, max string, equal to boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan('3', '3'),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThan(4, 3, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // less than or equal to
            'less than or equal to - above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(4, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than or equal to - value float, max float, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(3.2, 3.1),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than or equal to - value float, max int, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo(3.5, 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than or equal to - value string, max int, above boundary' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkLessThanOrEqualTo('4', 3),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'less than or equal to - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian)
                    => $guardian->checkLessThanOrEqualTo(4, 3, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // between
            'between - below min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(1, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between - above max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(5, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between - value float, bounds int, below min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(1.9999, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between - value float, bounds int, above max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(4.0001, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between - value int, bounds float, below min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(2, 2.5, 4.5),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between - value string, bounds int, above max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween('5', 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetween(1, 2, 4, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // between exclusive
            'between exclusive - equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(2, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between exclusive - equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(4, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between exclusive - value float, bounds float, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(2.5, 2.5, 4.5),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between exclusive - value float, bounds float, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive(4.5, 2.5, 4.5),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between exclusive - value string, bounds int, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive('2', 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between exclusive - value string, bounds int, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenExclusive('4', 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between exclusive - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian)
                    => $guardian->checkBetweenExclusive(2, 2, 4, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // between min exclusive
            'between min exclusive - equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(2, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between min exclusive - value float, bounds int, just below min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(1.9999, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between min exclusive - value float, bounds int, above max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(4.0001, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between min exclusive - value float, bounds float, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive(2.5, 2.5, 4.5),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between min exclusive - value string, bounds int, equal to min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMinExclusive('2', 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between min exclusive - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian)
                    => $guardian->checkBetweenMinExclusive(2, 2, 4, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
            // between max exclusive
            'between max exclusive - equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(4, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between max exclusive - value float, bounds int, just above max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(4.0001, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between max exclusive - value float, bounds int, below min' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(1.9999, 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between max exclusive - value float, bounds float, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive(4.5, 2.5, 4.5),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between max exclusive - value string, bounds int, equal to max' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian) => $guardian->checkBetweenMaxExclusive('4', 2, 4),
                'expectedExceptionClassName' => GuardianValidationException::class,
            ],
            'between max exclusive - with custom exception' => [
                'check' => static fn (NumericRangeRuleGuardian $guardian)
                    => $guardian->checkBetweenMaxExclusive(4, 2, 4, new CustomRuleException()),
                'expectedExceptionClassName' => CustomRuleException::class,
            ],
        ];
    }

    public function testFailedCheckWithDefaultCustomException(): void
    {
        $this->expectException(GuardianValidationException::class);

        try {
            $this->guardian->checkGreaterThan(2, 3);
        } catch (GuardianValidationException $exception) {
            self::assertSame('numeric_range_rule', $exception->getRuleCode());
            throw $exception;
        }
    }

    /**
     * @dataProvider getInvalidContextProvidedData
     * @param mixed $value
     */
    public function testCheckWithNonNumericValueThrowsGuardianExecutingRuleException($value): void
    {
        $this->expectException(GuardianExecutingRuleException::class);

        $this->guardian->checkGreaterThanOrEqualTo($value, 3);
    }

    public static function getInvalidContextProvidedData(): array
    {
        return [
            'context value - true' => [
                'value' => true,
            ],
            'context value - false' => [
                'value' => false,
            ],
            'context value - null' => [
                'value' => null,
            ],
            'context value - empty string' => [
                'value' => '',
            ],
            'context value - non numeric string' => [
                'value' => 'abc',
            ],
            'context value - not empty array' => [
                'value' => [123,],
            ],
            'context value - empty array' => [
                'value' => [],
            ],
            'context value - object' => [
                'value' => new stdClass(),
            ],
            'context value - callable' => [
                'value' => static function () {
                },
            ],
            'context value - resource' => [
                'value' => tmpfile(),
            ],
        ];
    }

    /**
     * @dataProvider getInvalidConfigurationProvidedData
     */
    public function testCheckWithInvalidConfigurationThrowsInvalidRuleContextException(callable $check): void
    {
        $this->expectException(InvalidRuleContextException::class);

        $check($this->guardian);
    }

    public static function getInvalidConfigurationProvidedData(): array
    {
        return [
            'between with min greater than max' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkBetween(3, 4, 2),
            ],
            'between exclusive with min greater than max' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkBetweenExclusive(3, 4, 2),
            ],
            'between exclusive with equal bounds' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkBetweenExclusive(3, 3, 3),
            ],
            'between min exclusive with equal bounds' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkBetweenMinExclusive(3, 3, 3),
            ],
            'between max exclusive with equal bounds' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkBetweenMaxExclusive(3, 3, 3),
            ],
            'greater than with non numeric bound' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkGreaterThan(3, 'abc'),
            ],
            'less than with non numeric bound' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkLessThan(3, 'abc'),
            ],
            'between with non numeric bounds' => [
                'check' => static fn (NumericRangeRuleGuardian $g) => $g->checkBetween(3, 'a', 'b'),
            ],
        ];
    }

    public function testFailedCheckCauseGuardianThrowsGuardianExecutingRuleException(): void
    {
        $this->expectException(GuardianExecutingRuleException::class);

        $guardian = new NumericRangeRuleGuardian(
            $this->getGuardianThrowsExceptionOnCheck(GuardianExecutingRuleException::class)
        );

        $guardian->checkGreaterThan(4, 3);
    }

    public function testFailedCheckCauseGuardianThrowsNotExpectedException(): void
    {
        $this->expectException(Throwable::class);

        $guardian = new NumericRangeRuleGuardian(
            $this->getGuardianThrowsExceptionOnCheck(Throwable::class)
        );

        $guardian->checkGreaterThan(4, 3);
    }

    /**
     * @return Guardian|MockObject
     */
    private function getGuardianThrowsExceptionOnCheck(string $expectedExceptionClass): Guardian
    {
        $guardian = $this->getGuardianMock();

        $guardian
            ->expects(self::once())
            ->method('check')
            ->willThrowException($this->createMock($expectedExceptionClass));

        return $guardian;
    }

    /**
     * @return Guardian|MockObject
     */
    private function getGuardianMock(): Guardian
    {
        /** @var Guardian|MockObject $mock */
        $mock = $this->createMock(Guardian::class);

        return $mock;
    }
}
