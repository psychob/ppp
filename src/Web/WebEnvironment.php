<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web;

    use PsychoB\WebFramework\Bios\ApplicationInterface;
    use PsychoB\WebFramework\Bios\EnvironmentInterface;

    class WebEnvironment implements EnvironmentInterface
    {
        /** @inheritDoc */
        public static function getLikelihoodOfCurrentEnvironment(): int
        {
            return PHP_INT_MAX;
        }

        private ApplicationInterface $app;

        public function __construct(string $appDir)
        {
            $this->app = new WebApplication($appDir, $this);
        }

        /** @inheritDoc */
        public function execute(callable $onLoad)
        {
            return $onLoad($this->app);
        }
    }
