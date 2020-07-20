<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Utility;

    use Webmozart\PathUtil\Path as WebmozartPath;

    /// TODO: Make utility class that will allow this to be mocked (like facades from laravel)
    class Path
    {
        public static function join(string... $paths): string
        {
            return WebmozartPath::join($paths);
        }

        public static function real(string... $paths): string
        {
            return realpath(self::join(...$paths));
        }

        public static function directoryExists(string $path): bool
        {
            return is_dir($path);
        }

        public static function canonicalize(string $path): string
        {
            return WebmozartPath::canonicalize($path);
        }
    }
