<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Enum;

    use PsychoB\WebFramework\Core\Enum\AbstractEnum;

    class HttpCodeEnum extends AbstractEnum
    {
        public const HTTP_FORBIDDEN = 401;
        public const HTTP_NOT_FOUND = 404;

        public const CODE_404 = self::HTTP_NOT_FOUND;
    }
