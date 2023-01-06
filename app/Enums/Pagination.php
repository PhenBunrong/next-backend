<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static limit()
 * @method static static unlimited()
 * @method static static paginate()
 * @method static static simplePaginate()
 */
final class Pagination extends Enum
{
    public const limit = 20;

    public const unlimited = -1;

    public const paginate = 'paginate';

    public const simplePaginate = 'simplePaginate';

    /**
     * @return string[]
     */
    public static function getPaginates(): array
    {
        return [self::paginate, self::simplePaginate];
    }
}
