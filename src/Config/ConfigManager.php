<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Config;

    use PsychoB\WebFramework\DependencyInjector\Hints\ConstructorHint;
    use PsychoB\WebFramework\DependencyInjector\Hints\InjectorHint;
    use PsychoB\WebFramework\Utility\Path;
    use Symfony\Component\Finder\Finder;

    class ConfigManager implements ConstructorHint
    {
        protected string $appPath;
        protected string $frameworkPath;
        protected OptionManager $optionManager;

        public function __construct(string $appPath, string $frameworkPath, OptionManager $optionManager)
        {
            $this->appPath = Path::join($appPath, 'config');
            $this->frameworkPath = Path::join($frameworkPath, 'config');

            $this->optionManager = $optionManager;
        }

        public function options(): OptionManager
        {
            return $this->optionManager;
        }

        public function routes(): iterable
        {
            return $this->loadConfigFiles('routes', 'proute');
        }

        private function loadConfigFiles(string $subPath, string $fileExt): iterable
        {
            $finder = new Finder();

            $inDir = [];
            $appPath = Path::join($this->appPath, $subPath);
            $fwkPath = Path::join($this->frameworkPath, $subPath);

            if (Path::directoryExists($appPath)) {
                $inDir[] = $appPath;
            }
            if (Path::directoryExists($fwkPath)) {
                $inDir[] = $fwkPath;
            }

            $finder
                ->ignoreUnreadableDirs(true)
                ->in($inDir)
                ->name('*.'.$fileExt);

            foreach ($finder as $file) {
                yield $file;
            }
        }

        public static function ppp_internal__GetConstructorHint(): array
        {
            return [
                InjectorHint::appPath(),
                InjectorHint::frameworkPath(),
            ];
        }
    }
