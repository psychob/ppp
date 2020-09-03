<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use PsychoB\WebFramework\Collection\Traits\OneTimeInitializedContainerTrait;

    abstract class AbstractSortIterator implements StreamIteratorInterface
    {
        use OneTimeInitializedContainerTrait;

        protected abstract function initializeContainer(): array;
    }
