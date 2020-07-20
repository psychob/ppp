<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector;

    use Psr\Container\ContainerInterface;

    /**
     * @mixin ReadonlyServiceContainerInterface
     */
    trait ServiceContainerPsrCacheTrait
    {
        private ?ContainerInterface $psrCache = null;


        public function psr(): ContainerInterface
        {
            if ($this->psrCache === null) {
                $this->psrCache = new PsrServiceContainerAdapter($this);
            }

            return $this->psrCache;
        }
    }
