<?php

namespace App\Enums;

enum TechType: string
{
    case LANGUAGE = 'language';
    case FRAMEWORK = 'framework';
    case DATABASE = 'database';
    case FRONTEND = 'frontend';
    case BACKEND = 'backend';
    case TOOL = 'tool';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::LANGUAGE => 'Programming Language',
            self::FRAMEWORK => 'Framework',
            self::DATABASE => 'Database',
            self::FRONTEND => 'Frontend Technology',
            self::BACKEND => 'Backend Technology',
            self::TOOL => 'Tool / DevOps',
            self::OTHER => 'Other',
        };
    }
}
