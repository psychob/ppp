<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Container;

    use Psr\Container\ContainerInterface as PsrContainerInterface;

    class PsrContainerAdapter implements PsrContainerInterface
    {
        protected ServiceContainerInterface $container;

        public function __construct(ServiceContainerInterface $container)
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
            return $this->container->has($id);
        }
    }
