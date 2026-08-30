<?php

namespace Aegisora\RuleGuardians\NumericRangeRule;

use Aegisora\Guardian\Guardian;

class NumericRangeRuleGuardian
{
    private Guardian $guardian;

    public function __construct(
        Guardian $guardian
    ) {
        $this->guardian = $guardian;
    }
}
