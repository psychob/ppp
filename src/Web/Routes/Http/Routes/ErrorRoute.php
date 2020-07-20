<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http\Routes;

    use PsychoB\WebFramework\Web\Routes\Http\Controllers\ErrorController;
    use PsychoB\WebFramework\Web\Routes\Route;

    class ErrorRoute extends Route
    {
        public function __construct()
        {
            parent::__construct("ppp://error", ["ANY"], "*", ErrorController::class, 'handle');
        }
    }
