# Aegisora Numeric Range Rule Guardian

[![Latest Version](https://img.shields.io/packagist/v/aegisora/numeric-range-rule-guardian?style=flat-square)](https://packagist.org/packages/aegisora/numeric-range-rule-guardian)
[![Total Downloads](https://img.shields.io/packagist/dt/aegisora/numeric-range-rule-guardian?style=flat-square)](https://packagist.org/packages/aegisora/numeric-range-rule-guardian)
![Code Coverage Badge](./badge.svg)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
![PHPStan Badge](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)

Numeric Range Rule Guardian provides simple shortcuts for ensuring a numeric value falls within an expected range using `aegisora/guardian` and `aegisora/numeric-range-rule`.

It is designed for cases where you want to quickly check whether a value is greater than, less than, or between given bounds without manually creating validation pipelines.

This package is built on top of:

- [aegisora/guardian](https://github.com/Aegisora/guardian)
- [aegisora/numeric-range-rule](https://github.com/Aegisora/numeric-range-rule)

---

## ✨ Features
- 🔹 Simple shortcut API for `NumericRangeRule`
- 🔹 Validates lower bounds (`checkGreaterThan`, `checkGreaterThanOrEqualTo`)
- 🔹 Validates upper bounds (`checkLessThan`, `checkLessThanOrEqualTo`)
- 🔹 Validates ranges with inclusive/exclusive bounds (`checkBetween`, `checkBetweenExclusive`, `checkBetweenMinExclusive`, `checkBetweenMaxExclusive`)
- 🔹 Works with integers, floats and numeric strings
- 🔹 Uses `aegisora/guardian` internally
- 🔹 Uses `aegisora/numeric-range-rule` internally
- 🔹 Supports custom validation exceptions
- 🔹 Fully compatible with the Aegisora ecosystem
- 🔹 Ready to use out of the box

---

## 📦 Installation

```shell
composer require aegisora/numeric-range-rule-guardian
```

---

## 🚀 Core Concept

This package wraps the common validation flow:

```php
$guardian->check($value, NumericRangeRule::createBetween($min, $max), new InvalidValueException());
```

into a dedicated shortcut class:

```php
$numericRangeRuleGuardian->checkBetween($value, $min, $max, new InvalidValueException());
```

Instead of manually creating `NumericRangeRule` and passing it to `Guardian`, you can use `NumericRangeRuleGuardian` directly.

---

## 🏗️ Basic Usage

```php
use Aegisora\Guardian\Exceptions\GuardianValidationException;
use Aegisora\Guardian\Guardian;
use Aegisora\RuleGuardians\NumericRangeRule\NumericRangeRuleGuardian;

$guardian = new Guardian();

$numericRangeRuleGuardian = new NumericRangeRuleGuardian($guardian);

try {
    $numericRangeRuleGuardian->checkBetween(3, 2, 4);
    // value is within the range
} catch (GuardianValidationException $exception) {
    // value is out of the range
}
```

---

## 🧩 Usage with Custom Exception

You may provide your own exception for validation failure.

```php
use Aegisora\Guardian\Guardian;
use Aegisora\RuleGuardians\NumericRangeRule\NumericRangeRuleGuardian;
use App\Exceptions\InvalidValueException;

$guardian = new Guardian();

$numericRangeRuleGuardian = new NumericRangeRuleGuardian($guardian);

$numericRangeRuleGuardian->checkGreaterThan(1, 3, new InvalidValueException());
```

If the value is out of the range, the provided exception will be thrown.

This is useful when validation errors should have domain-specific meaning.

---

## 🧪 Example in Application Service

```php
use Aegisora\RuleGuardians\NumericRangeRule\NumericRangeRuleGuardian;
use App\Exceptions\InvalidValueException;

final class ProductService
{
    private NumericRangeRuleGuardian $numericRangeRuleGuardian;

    public function __construct(
        NumericRangeRuleGuardian $numericRangeRuleGuardian
    ) {
        $this->numericRangeRuleGuardian = $numericRangeRuleGuardian;
    }

    /**
     * @param numeric $price
     */
    public function process($price): void
    {
        $this->numericRangeRuleGuardian->checkGreaterThan($price, 0, new InvalidValueException());

        // business logic for a value within the expected range
    }
}
```

---

## 🚨 Exceptions

This package does not define its own exception types. All errors are raised by the underlying `aegisora/guardian` package.

Both exceptions extend the abstract base class
`Aegisora\Guardian\Exceptions\GuardianException`,
so you can catch every validation error with a single `catch`:

```php
use Aegisora\Guardian\Exceptions\GuardianException;

try {
    $numericRangeRuleGuardian->checkBetween($value, $min, $max);
} catch (GuardianException $exception) {
    // handles GuardianValidationException and GuardianExecutingRuleException
}
```

### `GuardianValidationException`

Thrown when validation fails and no custom exception is provided.

```php
use Aegisora\Guardian\Exceptions\GuardianValidationException;

try {
    $numericRangeRuleGuardian->checkGreaterThan(2, 3);
} catch (GuardianValidationException $exception) {
    echo $exception->getRuleCode(); // "numeric_range_rule"
}
```

### `GuardianExecutingRuleException`

Thrown when the underlying rule execution fails, for example when the checked value is not numeric.

`Aegisora\Guardian\Exceptions\GuardianExecutingRuleException`

---

## 🧩 API

All methods share the same contract: they return `void` and communicate results through exceptions only — nothing is returned on success and an exception is thrown on failure:
- `GuardianValidationException` — validation failed and no custom exception was provided
- `GuardianExecutingRuleException` — the underlying rule failed to execute (e.g. the value is not numeric)
- the provided custom exception — validation failed and a custom exception was passed

Each method also throws `Aegisora\RuleContract\Exceptions\InvalidRuleContextException` when the range is configured with invalid bounds (a non-numeric bound, `$min` greater than `$max`, or equal bounds where at least one side is exclusive).

### `NumericRangeRuleGuardian::checkGreaterThan()`

```php
/**
 * @param mixed $value
 * @param numeric $min
 */
public function checkGreaterThan(
    $value,
    $min,
    ?\Throwable $exception = null
): void
```

Valid when `$value > $min`.

### `NumericRangeRuleGuardian::checkGreaterThanOrEqualTo()`

```php
/**
 * @param mixed $value
 * @param numeric $min
 */
public function checkGreaterThanOrEqualTo(
    $value,
    $min,
    ?\Throwable $exception = null
): void
```

Valid when `$value >= $min`.

### `NumericRangeRuleGuardian::checkLessThan()`

```php
/**
 * @param mixed $value
 * @param numeric $max
 */
public function checkLessThan(
    $value,
    $max,
    ?\Throwable $exception = null
): void
```

Valid when `$value < $max`.

### `NumericRangeRuleGuardian::checkLessThanOrEqualTo()`

```php
/**
 * @param mixed $value
 * @param numeric $max
 */
public function checkLessThanOrEqualTo(
    $value,
    $max,
    ?\Throwable $exception = null
): void
```

Valid when `$value <= $max`.

### `NumericRangeRuleGuardian::checkBetween()`

```php
/**
 * @param mixed $value
 * @param numeric $min
 * @param numeric $max
 */
public function checkBetween(
    $value,
    $min,
    $max,
    ?\Throwable $exception = null
): void
```

Valid when `$min <= $value <= $max` (both bounds inclusive).

### `NumericRangeRuleGuardian::checkBetweenExclusive()`

```php
/**
 * @param mixed $value
 * @param numeric $min
 * @param numeric $max
 */
public function checkBetweenExclusive(
    $value,
    $min,
    $max,
    ?\Throwable $exception = null
): void
```

Valid when `$min < $value < $max` (both bounds exclusive).

### `NumericRangeRuleGuardian::checkBetweenMinExclusive()`

```php
/**
 * @param mixed $value
 * @param numeric $min
 * @param numeric $max
 */
public function checkBetweenMinExclusive(
    $value,
    $min,
    $max,
    ?\Throwable $exception = null
): void
```

Valid when `$min < $value <= $max` (lower bound exclusive, upper bound inclusive).

### `NumericRangeRuleGuardian::checkBetweenMaxExclusive()`

```php
/**
 * @param mixed $value
 * @param numeric $min
 * @param numeric $max
 */
public function checkBetweenMaxExclusive(
    $value,
    $min,
    $max,
    ?\Throwable $exception = null
): void
```

Valid when `$min <= $value < $max` (lower bound inclusive, upper bound exclusive).

Common parameters:
- `$value` *(mixed)* — value to validate; considered valid when it is numeric and satisfies the configured range
- `$min` / `$max` *(numeric)* — range bounds
- `$exception` *(?\Throwable, default `null`)* — optional custom exception thrown on validation failure

Example:

```php
$numericRangeRuleGuardian->checkBetween(3, 2, 4);
```

With custom exception:

```php
$numericRangeRuleGuardian->checkBetween(5, 2, 4, new InvalidValueException());
```

---

## 🏛️ Architecture

This package is a small shortcut layer over the Aegisora validation pipeline.

Flow:
1. A `NumericRangeRuleGuardian::check*()` method is called
2. The matching `NumericRangeRule::create*()` rule is created
3. `Guardian` executes the rule
4. If validation succeeds, execution continues normally
5. If validation fails, custom exception or `GuardianValidationException` is thrown
6. If rule execution fails, `GuardianExecutingRuleException` is thrown

Internal flow:

```text
Value → NumericRangeRuleGuardian → Guardian → NumericRangeRule → Result → Exception
```

---

## 🔗 Related Packages

- [aegisora/guardian](https://github.com/Aegisora/guardian) — validation execution orchestrator
- [aegisora/numeric-range-rule](https://github.com/Aegisora/numeric-range-rule) — rule-based numeric range validation
- [aegisora/rule-contract](https://github.com/Aegisora/rule-contract) — base rule contract and validation result architecture

---

## ⚖️ License

This package is open-source and licensed under the MIT License. See the LICENSE for details.

---

## 🌱 Contributing

Contributions are welcome and greatly appreciated!. See the CONTRIBUTING for details.

---

## 🌟 Support

If you find this project useful, please consider giving it a star on GitHub!

It helps the project grow and motivates further development.

---
