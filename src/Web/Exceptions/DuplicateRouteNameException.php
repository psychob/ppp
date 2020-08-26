<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Exceptions;

    use PsychoB\WebFramework\Web\Http\Route\Route;
    use Throwable;

    class DuplicateRouteNameException extends DuplicateRouteException
    {
        private string $name;

        public function __construct(
            string $name,
            Route $new,
            Route $current,
            $message = 'Trying to insert route with conflicting name',
            Throwable $previous = null
        ) {
            parent::__construct($new, $current, $message, $previous);

            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }
    }
