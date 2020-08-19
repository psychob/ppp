<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Container;

    use Psr\Container\ContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\ElementNotFoundException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\FailedToFetchElementException;

    /**
     * Readonly service container
     *
     * @author Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */
    interface ReadonlyServiceContainerInterface
    {
        /**
         * Check if container has $key in it. This method should not throw
         *
         * @param string $key
         * @return bool
         */
        public function has(string $key): bool;

        /**
         * Get $key from container.
         *
         * @param string $key
         * @return object
         *
         * @throws ElementNotFoundException      When element doesn't exist inside container
         * @throws FailedToFetchElementException When there was unspecified error while fetching item from container
         */
        public function get(string $key): object;

        /**
         * Get $key from container or $default value if value doesn't exist.
         *
         * @param string $key
         * @param mixed $default
         *
         * @return mixed
         *
         * @throws FailedToFetchElementException When there was unspecified error while fetching item from container
         */
        public function getOr(string $key, $default = null);

        /**
         * Get PSR-11 compatible version of this container.
         *
         * @return ContainerInterface
         */
        public function psr(): ContainerInterface;
    }
