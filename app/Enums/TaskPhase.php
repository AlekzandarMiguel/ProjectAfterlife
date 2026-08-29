<?php

namespace App\Enums;

enum TaskPhase: string
{
    case ASSESSMENT = 'assessment';
    case REPAIR = 'repair';
    case DEVELOPMENT = 'development';
    case TESTING = 'testing';
    case DEPLOYMENT = 'deployment';

    public function label(): string
    {
        return match($this) {
            self::ASSESSMENT => 'Phase 1: Assessment',
            self::REPAIR => 'Phase 2: Repair & Refactor',
            self::DEVELOPMENT => 'Phase 3: Development',
            self::TESTING => 'Phase 4: Testing & Security',
            self::DEPLOYMENT => 'Phase 5: Finalization & Deployment',
        };
    }
}
