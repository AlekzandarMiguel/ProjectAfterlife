<?php

namespace App\Enums;

enum FileType: string
{
    case SOURCE_CODE_ZIP = 'source_code_zip';
    case RELEASE_PACKAGE = 'release_package';
    case DOCUMENTATION = 'documentation';
    case README = 'readme';
    case DATABASE_SQL = 'database_sql';
    case ASSET = 'asset';

    public function label(): string
    {
        return match($this) {
            self::SOURCE_CODE_ZIP => 'Source Code Archive (ZIP)',
            self::RELEASE_PACKAGE => 'Version Release Package (ZIP)',
            self::DOCUMENTATION => 'Documentation Document',
            self::README => 'README File',
            self::DATABASE_SQL => 'Database SQL Dump',
            self::ASSET => 'Asset Bundle',
        };
    }
}
