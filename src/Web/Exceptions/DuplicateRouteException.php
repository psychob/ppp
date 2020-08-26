<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Exceptions;

    use PsychoB\WebFramework\Core\BaseException;
    use PsychoB\WebFramework\Web\Http\Route\Route;
    use Throwable;

    class DuplicateRouteException extends BaseException
    {
        private Route $new;
        private Route $current;

        public function __construct(
            Route $new,
            Route $current,
            string $message = 'Trying to insert route that was already inside container',
            Throwable $previous = null
        ) {
            parent::__construct(
                $message,
                0,
                $previous
            );

            $this->new = $new;
            $this->current = $current;
        }

        public function getNew(): Route
        {
            return $this->new;
        }

        public function getCurrent(): Route
        {
            return $this->current;
        }
    }
