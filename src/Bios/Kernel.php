<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Bios;

    use PsychoB\WebFramework\Utility\Path;
    use PsychoB\WebFramework\Web\WebEnvironment;

    /**
     * Class Kernel
     *
     * @author Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     * @since  0.1
     */
    class Kernel
    {
        protected static bool $debugMode = false;

        public static function setDebugMode(bool $enable = true): void
        {
            self::$debugMode = $enable;
        }

        public static function boot(string $appDir, callable $onBoot): int
        {
            self::setUpErrorHandler();

            if (self::$debugMode) {
                ini_set('display_errors', true);
                error_reporting(E_ALL);
                set_time_limit(1);
            }

            $env = self::createEnvironment($appDir);

            $ret = $onBoot($env);
            if ($ret === null) {
                $ret = 0;
            }

            dump($env);

            return $ret;
        }

        private static function createEnvironment(string $appDir): EnvironmentInterface
        {
            return new WebEnvironment($appDir);
        }

        public static function defaultRun(string $dir): int
        {
            return Kernel::boot(
                Path::real($dir, '..'),
                function (EnvironmentInterface $environment) {
                    return $environment->execute(
                        function (ApplicationInterface $app) {
                            return $app->execute();
                        }
                    );
                }
            );
        }

        private static function setUpErrorHandler(): void
        {
            set_error_handler(function (int $errNo, string $errStr, ?string $errFile, ?int $errLine) {
                throw new \ErrorException($errStr, $errNo, $errNo, $errFile, $errLine);
            });
        }

        public static function isDebug(): bool
        {
            return self::$debugMode;
        }
    }
