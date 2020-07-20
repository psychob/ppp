<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\DependencyInjector\Hints;

    class InjectorHint
    {
        public const CALLER_NAME = 'caller/name';
        public const APPLICATION_PATH = 'path/app';
        public const FRAMEWORK_PATH = 'path/framework';
        public const REQUESTED_ROUTE = 'request/route';

        protected string $name;

        public function __construct(string $name)
        {
            $this->name = $name;
        }

        public static function callerClass(): self
        {
            return new InjectorHint(self::CALLER_NAME);
        }

        public static function appPath(): self
        {
            return new self(self::APPLICATION_PATH);
        }

        public static function frameworkPath(): self
        {
            return new self(self::FRAMEWORK_PATH);
        }

        public static function currentRoute(): self
        {
            return new self(self::REQUESTED_ROUTE);
        }

        public function getTag()
        {
            return $this->name;
        }
    }
