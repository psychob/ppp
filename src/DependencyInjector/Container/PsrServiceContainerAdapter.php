<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Container;

    use Psr\Container\ContainerInterface;

    /**
     * Adapter for compatibility with PSR-11
     *
     * @author Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */
    class PsrServiceContainerAdapter implements ContainerInterface
    {
        private ReadonlyServiceContainerInterface $container;

        public function __construct(ReadonlyServiceContainerInterface $container)
        {
            $this->container = $container;
        }

        /** @inheritDoc */
        public function get($id)
        {
            return $this->container->get($id);
        }

        /** @inheritDoc */
        public function has($id)
        {
            try {
                return $this->container->has($id);
            } catch (\Exception $e) {
                return false;
            }
        }
    }
