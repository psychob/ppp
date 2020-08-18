<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Utility;

    class Path
    {
        public static function getExt(string $path): ?string
        {
            return pathinfo($path, PATHINFO_EXTENSION);
        }

        public static function resolve(string $dir): ?string
        {
            $ret = realpath($dir);

            return $ret === false ? null : $ret;
        }

        public static function joinResolve(string... $dir): ?string
        {
            return Path::resolve(self::join(...$dir));
        }

        public static function join(string... $dir): string
        {
            $ret = '';

            foreach ($dir as $str) {
                $ret .= DIRECTORY_SEPARATOR . trim($str, '\\/');
            }

            return $ret;
        }
    }
