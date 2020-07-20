<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing;

    use PHPUnit\Framework\Constraint\Exception as ExceptionConstraint;
    use PHPUnit\Framework\TestCase as PhpUnitTestCase;

    /**
     * @mixin PhpUnitTestCase
     */
    trait MatchExceptionTrait
    {
        protected function matchThrownException(callable $func, string $class, array $properties)
        {
            try {
                $func();
            } catch (\Throwable $t) {
                $this->assertThat($t, new ExceptionConstraint(
                    $class
                ));

                foreach ($properties as $name => $value) {
                    $this->assertSame($value, $t->{$name}());
                }
            }
        }
    }
