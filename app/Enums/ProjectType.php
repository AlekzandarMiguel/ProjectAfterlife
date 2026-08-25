<?php

namespace App\Enums;

enum ProjectType: string
{
    case WEB = 'web';
    case MOBILE = 'mobile';
    case DESKTOP = 'desktop';
    case CLI = 'cli';
    case LIBRARY = 'library';
    case API = 'api';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::WEB => 'Web Application',
            self::MOBILE => 'Mobile App',
            self::DESKTOP => 'Desktop Application',
            self::CLI => 'CLI Tool',
            self::LIBRARY => 'Library / Package',
            self::API => 'Backend / API',
            self::OTHER => 'Other Software',
        };
    }
}
