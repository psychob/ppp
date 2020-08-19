<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Container;

    use Psr\Container\ContainerInterface;

    trait ServiceContainerPsrCacheTrait
    {
        private ?ContainerInterface $psrCache = null;

        /** @inheritDoc */
        public function psr(): ContainerInterface
        {
            /** @var ReadonlyServiceContainerInterface $this */
            if ($this->psrCache === null) {
                $this->psrCache = new PsrServiceContainerAdapter($this);
            }

            return $this->psrCache;
        }
    }
