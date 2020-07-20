<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    use PsychoB\WebFramework\Collection\ArrayCollection;
    use PsychoB\WebFramework\Collection\CollectionInterface;

    function collect($arr): CollectionInterface
    {
        if ($arr instanceof Collectioninterface) {
            return new ArrayCollection($arr->toArray());
        } else {
            return new ArrayCollection($arr);
        }
    }

