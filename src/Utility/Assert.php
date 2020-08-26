<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    use PsychoB\WebFramework\Utility\Enum\AssertPrimitiveTypeEnum;
    use PsychoB\WebFramework\Utility\Exceptions\InvalidArgumentException;

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

        public static function checkIfTypeIs($value, $type): bool
        {
            if (is_string($type)) {
                return is_subclass_of($value, $type, true);
            }

            if (is_integer($type)) {
                switch ($type) {
                    case AssertPrimitiveTypeEnum::ARRAY:
                        return is_array($value);
                }
            }

            dump($value, $type);
            return false;
        }
    }
