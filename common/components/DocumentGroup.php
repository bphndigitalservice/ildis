<?php

namespace common\components;

class DocumentGroup
{
    public const LEGISLATION_FORMATION = 'legislation_formation';

    public static function labels(): array
    {
        return [
            self::LEGISLATION_FORMATION => 'Dokumen Penyusunan PUU',
        ];
    }

    public static function label(string $slug): string
    {
        return self::labels()[$slug] ?? $slug;
    }
}
