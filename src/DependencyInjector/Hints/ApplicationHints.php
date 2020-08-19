<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Hints;

    class ApplicationHints implements HintInterface
    {
        public const APPLICATION_PATH = 'app/path';
        public const FRAMEWORK_PATH   = 'framework/path';

        private string $hint;

        private function __construct(string $hint)
        {
            $this->hint = $hint;
        }

        public function getHint(): string
        {
            return $this->hint;
        }

        public static function frameworkPath(): self
        {
            return new self(self::FRAMEWORK_PATH);
        }

        public static function applicationPath(): self
        {
            return new self(self::APPLICATION_PATH);
        }
    }
