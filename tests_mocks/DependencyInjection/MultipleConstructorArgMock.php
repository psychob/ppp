<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Mocks\PsychoB\WebFramework\DependencyInjection;

    class MultipleConstructorArgMock
    {
        public HaveConstructorMock $mock;
        public ConstructorlessMock $ctrlMock;
        public SimpleConstructorMock $simple;

        public function __construct(
            HaveConstructorMock $mock,
            ConstructorlessMock $ctrlMock,
            SimpleConstructorMock $simple
        ) {
            $this->mock = $mock;
            $this->ctrlMock = $ctrlMock;
            $this->simple = $simple;
        }
    }
