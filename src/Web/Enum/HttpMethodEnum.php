<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Enum;

    use PsychoB\WebFramework\Core\Enum\AbstractEnum;

    class HttpMethodEnum extends AbstractEnum
    {
        public const GET = 'GET';
        public const POST = 'POST';
        public const PUT = 'PUT';
        public const DELETE = 'DELETE';
        public const OPTIONS = 'OPTIONS';
    }
