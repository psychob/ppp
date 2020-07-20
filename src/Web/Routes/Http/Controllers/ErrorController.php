<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http\Controllers;

    class ErrorController extends Controller
    {
        public function handle()
        {
            return $this->getView('framework.error');
        }
    }
