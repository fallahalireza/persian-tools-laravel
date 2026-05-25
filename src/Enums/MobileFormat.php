<?php

namespace FallahAlireza\PersianTools\Enums;

enum MobileFormat: string
{
    case All = 'all';
    case ZeroCode = 'zero_code';   // 00989123456789
    case PlusCode = 'plus_code';   // +989123456789
    case Code = 'code';        // 989123456789
    case Zero = 'zero';        // 09123456789
    case Normal = 'normal';      // 9123456789
}
