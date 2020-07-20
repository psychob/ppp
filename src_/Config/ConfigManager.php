<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Config;

    use PsychoB\WebFramework\Environment\ApplicationHints;
    use PsychoB\WebFramework\Injector\ConstructorHint;
    use Symfony\Component\Finder\Finder;

    class ConfigManager implements ConstructorHint
    {
        private string $appDirectory;
        private OptionsManager $options;

        /**
         * ConfigManager constructor.
         *
         * @param string         $appDirectory
         * @param OptionsManager $options
         */
        public function __construct(string $appDirectory, OptionsManager $options)
        {
            $this->appDirectory = $appDirectory . DIRECTORY_SEPARATOR . 'config';
            $this->options = $options;
        }

        public function fetch(string $path): iterable
        {
            $finder = new Finder();
            $finder->files()
                   ->in($this->appDirectory . DIRECTORY_SEPARATOR . $path);

            foreach ($finder as $file) {
                yield $file;
            }
        }

        /** @inheritDoc */
        public static function _GetConstructorHint(): array
        {
            return [
                ApplicationHints::APP_DIRECTORY,
            ];
        }

        public function options(): OptionsManager
        {
            return $this->options;
        }
    }
