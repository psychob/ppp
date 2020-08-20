<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    class Assert
    {
        public static function argumentTypeInstanceOf($obj, array $allowedTypes, ?string $name = null): void
        {
            foreach ($allowedTypes as $type) {
                if (static::checkIfTypeIs($obj, $type)) {
                    return;
                }
            }

            throw new InvalidArgumentException($obj, $name, $allowedTypes);
        }
    }
