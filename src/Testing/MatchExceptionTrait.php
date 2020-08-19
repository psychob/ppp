<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing;

    use PHPUnit\Framework\Constraint\Exception as ExceptionConstraint;
    use PHPUnit\Framework\Constraint\LogicalAnd;
    use PHPUnit\Framework\TestCase as PhpUnitTestCase;
    use PsychoB\WebFramework\Testing\Constraints\ObjectPropertiesConstraint;

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
                $logicalAnd = new LogicalAnd();
                $logicalAnd->setConstraints([
                    new ExceptionConstraint($class),
                    new ObjectPropertiesConstraint($properties),
                ]);

                $this->assertThat($t, $logicalAnd);
            }
        }
    }
