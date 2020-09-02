<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Testing;

    use PHPUnit\Framework\TestCase as PhpUnitTestCase;
    use Mockery;

    class TestCase extends PhpUnitTestCase
    {
        use MatchExceptionTrait, ArrayAssertTrait;

        protected function tearDown(): void
        {
            if (class_exists('Mockery')) {
                if ($container = Mockery::getContainer()) {
                    $this->addToAssertionCount($container->mockery_getExpectationCount());
                }

                Mockery::close();
            }

            parent::tearDown();
        }
    }
