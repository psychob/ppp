<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    use PsychoB\WebFramework\Collection\Collection;
    use PsychoB\WebFramework\Collection\StreamInterface;

    if (!function_exists('collect')) {
        function collect($collection): StreamInterface
        {
            if ($collection instanceof StreamInterface) {
                return Collection::from($collection);
            }

            return new Collection($collection);
        }
    }
