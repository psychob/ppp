<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Config;

    use PsychoB\WebFramework\DependencyInjector\Hints\ConstructorHint;
    use PsychoB\WebFramework\DependencyInjector\Hints\InjectorHint;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Path;
    use PsychoB\WebFramework\Utility\Str;
    use Symfony\Component\Finder\Finder;

    class OptionManager implements ConstructorHint
    {
        private string $appPath;
        private string $frameworkPath;

        private array $configFilesApp = [];
        private array $configFilesFramework = [];
        private bool $configFilesLoaded = false;

        private array $values = [];

        public function __construct(string $appPath, string $frameworkPath)
        {
            $this->appPath = Path::join($appPath, 'config', 'options');
            $this->frameworkPath = Path::join($frameworkPath, 'config', 'options');
        }
        
        public function get(string $key, $default = null)
        {
            if (!$this->configFilesLoaded) {
                $this->preloadConfigFiles();
            }

            $arr = Str::split($key, '.');
            $filesToLoad = $this->decideWhichFilesToLoad($arr);

            if (count($filesToLoad)) {
                foreach ($filesToLoad as $file) {
                    $this->loadFile($file);
                }
            }

            return $this->getRecursive($this->values, $arr, $default);
        }

        private function getRecursive($values, array $elements, $default)
        {
            if (empty($elements)) {
                return $values;
            }

            $key = Arr::popFront($elements);

            if (Arr::hasKey($values, $key)) {
                return $this->getRecursive($values[$key], $elements, $default);
            }

            return $default;
        }

        private function preloadConfigFiles(): void
        {
            $finder = new Finder();

            $inDir = [];

            if (Path::directoryExists($this->appPath)) {
                $inDir[] = $this->appPath;
            }
            if (Path::directoryExists($this->frameworkPath)) {
                $inDir[] = $this->frameworkPath;
            }

            $finder
                ->ignoreUnreadableDirs(true)
                ->in($inDir)
                ->name('*.php');

            $frameworkPathLength = strlen($this->frameworkPath) + 1;
            $appPathLength = strlen($this->appPath) + 1;

            foreach ($finder as $file) {
                if (Str::startsWith($file->getPathname(), $this->appPath)) {
                    $this->configFilesApp[] = Path::canonicalize(Str::substr($file->getPathname(), $appPathLength));
                } else {
                    $this->configFilesFramework[] = Path::canonicalize(Str::substr($file->getPathname(), $frameworkPathLength));
                }
            }

            $this->configFilesLoaded = true;
        }

        public static function ppp_internal__GetConstructorHint(): array
        {
            return [
                InjectorHint::appPath(),
                InjectorHint::frameworkPath(),
            ];
        }

        private function decideWhichFilesToLoad(array $elements): array
        {
            $frameworkFiles = [];
            $frameworkFilesNo = [];

            $appFiles = [];
            $appFilesNo = [];

            foreach ($this->configFilesFramework as $no => $path) {
                $p = Str::split(Str::substr($path, 0, -4), DIRECTORY_SEPARATOR);

                $ret = Arr::stackCommonValues($elements, $p);
                if (!empty($ret)) {
                    $frameworkFiles[] = [Path::join($this->frameworkPath, $path), $ret];
                    $frameworkFilesNo[] = $no;
                }
            }

            $this->configFilesFramework = Arr::removeElementsByKey($this->configFilesFramework, $frameworkFilesNo, true);

            foreach ($this->configFilesApp as $no => $path) {
                $p = Str::split(Str::substr($path, 0, -4), DIRECTORY_SEPARATOR);

                $ret = Arr::stackCommonValues($elements, $p);
                if (!empty($ret)) {
                    $appFiles[] = [Path::join($this->appPath, $path), $ret];
                    $appFilesNo[] = $no;
                }
            }

            $this->configFilesApp = Arr::removeElementsByKey($this->configFilesApp, $appFilesNo, true);

            return Arr::stackElements($frameworkFiles, $appFiles);
        }

        private function loadFile(array $arg): void
        {
            [$path, $elements] = $arg;
            $arr = privateIncludeFile($path);

            $this->saveValues($this->values, $arr, $elements);
        }

        private function saveValues(array &$values, array $newValues, array $keys): void
        {
            if (empty($keys)) {
                foreach ($newValues as $key => $value) {
                    if (is_array($value)) {
                        if (!Arr::hasKey($values, $key)) {
                            $values[$key] = $value;
                        } else {
                            $this->saveValues($values[$key], $value, $keys);
                        }
                    } else {
                        $values[$key] = $value;
                    }
                }
            } else {
                $element = Arr::popFront($keys);

                if (!Arr::hasKey($values, $element)) {
                    $values[$element] = [];
                }

                $this->saveValues($values[$element], $newValues, $keys);
            }
        }
    }

    function privateIncludeFile(string $path): array
    {
        /** @noinspection PhpIncludeInspection */
        return require $path;
    }
