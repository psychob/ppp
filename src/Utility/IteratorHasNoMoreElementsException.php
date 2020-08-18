<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    use PsychoB\WebFramework\Core\BaseException;
    use Throwable;

    class IteratorHasNoMoreElementsException extends BaseException
    {
        /**
         * IteratorHasNoMoreElementsException constructor.
         *
         * @param array          $allIterators
         * @param array          $failedArray
         * @param Throwable|null $previous
         */
        public function __construct(array $allIterators, array $failedArray, ?Throwable $previous = null)
        {
            parent::__construct('Iterator has no more elements');
        }
    }
