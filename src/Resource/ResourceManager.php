<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Resource;

    use PsychoB\WebFramework\DependencyInjector\Hints\ApplicationHints;
    use PsychoB\WebFramework\DependencyInjector\Hints\DependencyInjectorHints;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Path;
    use Symfony\Component\Finder\Finder;

    class ResourceManager implements DependencyInjectorHints
    {
        private array $registeredDirectories = [];
        private array $registeredResourcesDirectories = [];

        public function __construct(string $appDir, string $frameDir)
        {
            $this->registerModule('app', $frameDir);
            $this->registerModule('ppp', $appDir);
        }

        public function iterateOver(string $pattern, string $category): iterable
        {
            $finder = new Finder();
            $finder->name($pattern)
                ->in($this->getDirectoriesForCategory($category));

            foreach ($finder->getIterator() as $file) {
                yield new ResourceFile($category, $file);
            }
        }

        private function getDirectoriesForCategory(string $category): array
        {
            return Arr::stream($this->registeredResourcesDirectories)
                ->mapValue(fn ($value) => Path::joinResolve($value, $category))
                ->filterOutEmpty()
                ->toArray();
        }

        public function registerModule(string $module, string $path): void
        {
            $this->registeredDirectories[] = $path;
            $res = Path::joinResolve($path, 'resources');
            if ($res) {
                $this->registeredResourcesDirectories[] = $res;
            }
        }

        public static function ppp_internal__GetHints(): array
        {
            return [
                '__construct' => [
                    'appDir' => ApplicationHints::applicationPath(),
                    'frameDir' => ApplicationHints::frameworkPath(),
                ],
            ];
        }
    }
