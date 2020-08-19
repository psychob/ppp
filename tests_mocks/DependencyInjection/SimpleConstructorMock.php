<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Mocks\PsychoB\WebFramework\DependencyInjection;

    class SimpleConstructorMock
    {
        public HaveConstructorMock $mock;

        public function __construct(HaveConstructorMock $mock)
        {
            $this->mock = $mock;
        }
    }
