<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web;

    use Psr\Container\ContainerInterface;
    use PsychoB\WebFramework\Bios\ApplicationInterface;
    use PsychoB\WebFramework\Bios\EnvironmentInterface;
    use PsychoB\WebFramework\Bios\Kernel;
    use PsychoB\WebFramework\Container\ServiceContainer;
    use PsychoB\WebFramework\Container\ServiceContainerInterface;
    use PsychoB\WebFramework\Debug\Timer;
    use PsychoB\WebFramework\DependencyInjector\DebugServiceInjector;
    use PsychoB\WebFramework\DependencyInjector\InjectorInterface;
    use PsychoB\WebFramework\DependencyInjector\ServiceInjector;
    use PsychoB\WebFramework\Utility\Path;
    use PsychoB\WebFramework\Web\Routes\RouteManager;

    class WebApplication implements ApplicationInterface
    {
        private string $appPath = "";
        private string $frameworkPath = "";
        private ServiceContainer $container;
        private InjectorInterface $injector;
        private Timer $dbgTimer;

        public function __construct(string $directory, EnvironmentInterface $environment)
        {
            $this->appPath = $directory;
            $this->frameworkPath = Path::real(__DIR__, '..', '..');
            $this->container = new ServiceContainer();
            $this->injector = new ServiceInjector($this->container);
            $this->dbgTimer = $this->injector->make(Timer::class, ['callerClass' => self::class]);

            $this->container->registerAll([
                ServiceContainer::class => $this->container,
                ServiceContainerInterface::class => $this->container,
                InjectorInterface::class => $this->injector,
                ServiceInjector::class => $this->injector,
                ApplicationInterface::class => $this,
                WebApplication::class => $this,
                EnvironmentInterface::class => $environment,
                WebEnvironment::class => $environment,
                ContainerInterface::class => $this->container->psr(),
            ]);

            if (Kernel::isDebug()) {
                $debugServiceInjector = $this->injector->make(DebugServiceInjector::class);

                $this->container->registerAll([
                    InjectorInterface::class => $debugServiceInjector,
                    ServiceInjector::class => $debugServiceInjector,
                ]);

                $this->injector = $debugServiceInjector;
            }
        }

        public function execute()
        {
            return $this->dbgTimer->timeIt(function () {
                /** @var RouteManager $routeManager */
                $routeManager = $this->make(RouteManager::class);

                $request = $routeManager->getRequestFromEnvironment();

                $response = $this->executeWithAppExceptionHandler(fn () => $routeManager->handleRequest($request));

                return $response->hasCodeRange(200) ? 0 : 1;
            }, 'executeApp');
        }

        public function make(string $class, array $arguments = []): object
        {
            return $this->injector->make($class, $arguments);
        }

        private function executeWithAppExceptionHandler(callable $param)
        {
//            try {
                return $param();
//            } catch (\Throwable $t) {
//                $eh = $this->make(ExceptionHandler::class);
//
//                try {
//                    $eh->handle($t);
//                } catch (\Throwable $ot) {
//                    $eh = $this->make(BasicExceptionHandler::class);
//
//                    $eh->handle($ot);
//                }
//            }

            return null;
        }

        public function getApplicationPath(): string
        {
            return $this->appPath;
        }

        public function getFrameworkPath(): string
        {
            return $this->frameworkPath;
        }
    }
