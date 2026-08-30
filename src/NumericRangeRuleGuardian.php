<?php

namespace Aegisora\RuleGuardians\NumericRangeRule;

use Aegisora\Guardian\Exceptions\GuardianExecutingRuleException;
use Aegisora\Guardian\Exceptions\GuardianValidationException;
use Aegisora\Guardian\Guardian;
use Aegisora\Rules\NumericRangeRule;
use Throwable;

class NumericRangeRuleGuardian
{
    private Guardian $guardian;

    public function __construct(
        Guardian $guardian
    ) {
        $this->guardian = $guardian;
    }

    /**
     * @param mixed $value
     * @param numeric $min
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkGreaterThan(
        $value,
        $min,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createGreaterThan($min), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $min
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkGreaterThanOrEqualTo(
        $value,
        $min,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createGreaterThanOrEqualTo($min), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $max
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkLessThan(
        $value,
        $max,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createLessThan($max), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $max
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkLessThanOrEqualTo(
        $value,
        $max,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createLessThanOrEqualTo($max), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $min
     * @param numeric $max
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkBetween(
        $value,
        $min,
        $max,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createBetween($min, $max), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $min
     * @param numeric $max
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkBetweenExclusive(
        $value,
        $min,
        $max,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createBetweenExclusive($min, $max), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $min
     * @param numeric $max
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkBetweenMinExclusive(
        $value,
        $min,
        $max,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createBetweenMinExclusive($min, $max), $exception);
    }

    /**
     * @param mixed $value
     * @param numeric $min
     * @param numeric $max
     * @throws GuardianExecutingRuleException
     * @throws GuardianValidationException
     * @throws Throwable
     */
    public function checkBetweenMaxExclusive(
        $value,
        $min,
        $max,
        ?Throwable $exception = null
    ): void {
        $this->guardian->check($value, NumericRangeRule::createBetweenMaxExclusive($min, $max), $exception);
    }
}
