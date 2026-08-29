<?php

namespace App\Enums;

enum DevelopmentStatus: string
{
    case CONCEPT = 'concept';
    case PROTOTYPE = 'prototype';
    case ALPHA = 'alpha';
    case BETA = 'beta';
    case BROKEN_PRODUCTION = 'broken_production';

    public function label(): string
    {
        return match($this) {
            self::CONCEPT => 'Concept / Planning',
            self::PROTOTYPE => 'Prototype / Proof of Concept',
            self::ALPHA => 'Alpha (Early Working Build)',
            self::BETA => 'Beta (Feature Incomplete)',
            self::BROKEN_PRODUCTION => 'Broken Production / Stalled',
        };
    }
}
