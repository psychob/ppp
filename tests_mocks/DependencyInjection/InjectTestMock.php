<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Mocks\PsychoB\WebFramework\DependencyInjection;

    class InjectTestMock
    {
        public static function argumentlessFunction(): bool
        {
            return true;
        }

        public static function simpleInts(int $a, int $b): int
        {
            return $a + $b;
        }

        private static function privateStaticMethod(): void
        {
        }

        private function privateNonStaticMethod(): void
        {
        }

        public function nonStaticMethod(): void
        {
        }
    }
