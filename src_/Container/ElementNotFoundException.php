<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Container;

    use RuntimeException;
    use Throwable;

    class ElementNotFoundException extends RuntimeException
    {
        protected string $elementId;
        protected array $availableKeys;

        /**
         * ElementNotFoundException constructor.
         *
         * @param string          $elementId
         * @param array           $availableKeys
         * @param string|null     $message
         * @param Throwable|null $previous
         */
        public function __construct(
            string $elementId,
            array $availableKeys,
            string $message = '',
            Throwable $previous = null
        ) {
            $this->elementId = $elementId;
            $this->availableKeys = $availableKeys;

            parent::__construct(sprintf('%s%s', 'Element not found in container: ', $message ?? ''), 0, $previous);
        }

        /**
         * @return string
         */
        public function getElementId(): string
        {
            return $this->elementId;
        }

        /**
         * @return array
         */
        public function getAvailableKeys(): array
        {
            return $this->availableKeys;
        }
    }
