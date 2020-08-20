<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    use PsychoB\WebFramework\Utility\Exceptions\NoMatchingException;

    class Fnc
    {
        /**
         * Execute $what and return result, but when it throws. Make adequate $response.
         *
         * Response can be one of the following:
         *  - single callable (it's executed when argument match exception thrown)
         *  - array of callables, where key is class that should match exception thrown
         *  - array of strings, where string is class that should be thrown instead of current exception
         *
         * @param callable                     $what
         * @param callable|callable[]|string[] $response
         * @return mixed
         *
         * @throws NoMatchingException When exception thrown has no good candidate
         */
        public static function rethrow(callable $what, $response)
        {
            try {
                return $what();
            } catch (\Throwable $t) {
                if (Fnc::isCallable($response)) {
                    $firstType = Arr::first(Fnc::getArgumentTypesOrdered($response));

                    if ($firstType !== null) {
                        if (Fnc::hasCommonAncestor($firstType->getName(), $t)) {
                            throw $response($t);
                        }
                    }

                    throw new NoMatchingException($response, 'Could not find matching exception', $t);
                }
            }
        }

        public static function isCallable($callable): bool
        {
            return is_callable($callable);
        }

        public static function getArgumentTypesOrdered($response): array
        {
            $reflected = new \ReflectionFunction($response);

            return Arr::toArray((function () use ($reflected): iterable {
                foreach ($reflected->getParameters() as $parameter) {
                    yield $parameter->getType();
                }
            })());
        }

        /**
         * @param object|string $earlier
         * @param object|string $later
         *
         * @return bool
         */
        public static function hasCommonAncestor($earlier, $later): bool
        {
            $earlierType = Str::is($earlier) ? $earlier : get_class($earlier);
            $laterType = Str::is($later) ? $later : get_class($later);

            if ($earlierType === $laterType) {
                return true;
            }

            if (is_subclass_of($laterType, $earlierType, true)) {
                return true;
            }

            return false;
        }

        public static function assert(bool $trigger, \Closure $onFalse): void
        {
            if (!$trigger) {
                throw $onFalse();
            }
        }
    }
