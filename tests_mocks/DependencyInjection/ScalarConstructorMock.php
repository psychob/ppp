<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Mocks\PsychoB\WebFramework\DependencyInjection;

    class ScalarConstructorMock
    {
        public int $foo;

        public function __construct(int $foo)
        {
            $this->foo = $foo;
        }
    }
