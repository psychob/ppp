<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Resource;

    use Symfony\Component\Finder\SplFileInfo;

    class ResourceFile
    {
        private string $category;
        private SplFileInfo $file;

        public function __construct(string $category, SplFileInfo $file)
        {
            $this->category = $category;
            $this->file = $file;
        }

        public function fetch(): string
        {
            return file_get_contents($this->file->getRealPath());
        }
    }
