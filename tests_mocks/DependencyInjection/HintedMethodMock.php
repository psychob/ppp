<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Mocks\PsychoB\WebFramework\DependencyInjection;

    use PsychoB\WebFramework\DependencyInjector\Hints\ApplicationHints;
    use PsychoB\WebFramework\DependencyInjector\Hints\DependencyInjectorHints;

    class HintedMethodMock implements DependencyInjectorHints
    {
        public static function ppp_internal__GetHints(): array
        {
            return [
                'getAppPath' => [ApplicationHints::applicationPath()],
                'getFramePath' =>  ['path' => ApplicationHints::frameworkPath()],
            ];
        }

        public function getAppPath(string $path): string
        {
            return $path;
        }

        public function getFramePath(string $path): string
        {
            return $path;
        }

        public function invalidHint(string $path): string
        {
            return $path;
        }
    }
