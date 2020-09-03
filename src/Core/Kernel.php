<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Core;

    use PsychoB\WebFramework\Utility\Path;
    use PsychoB\WebFramework\Web\Environment;

    class Kernel
    {
        private static string $appDir;

        public static function boot(callable $onBoot, string $appDirectory): int
        {
            self::$appDir = $appDirectory;

            $environment = self::getCurrentEnvironment($appDirectory);

            $ret = $onBoot($environment);

            dump($environment);

            if ($ret === null) {
                return 0;
            }

            return intval($ret);
        }

        public static function setDebugMode(bool $value): void
        {
            if ($value) {
                ini_set('display_errors', true);
                set_time_limit(1);
            }
        }

        private static function getCurrentEnvironment(string $appDirectory): EnvironmentInterface
        {
            return new Environment($appDirectory);
        }

        public static function getApplicationPath(): string
        {
            return Path::resolve(static::$appDir);
        }

        public static function getFrameworkPath(): string
        {
            return Path::joinResolve(__DIR__, '..', '..', '..');
        }
    }
