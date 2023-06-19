<?php
declare(strict_types=1);

namespace PSA\Controllers;

use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher\ExceptionHandlerInterface;
use Phalcon\Mvc\Controller;

class ErrorController extends ControllerBase
{


    public function initialize()
    {
        // check auth users
        if ($this->auth->getIdentity()) {
            $this->view->setTemplateBefore('private');
        } else {
            $this->view->setTemplateBefore('public');
        }

    }

    public function show404Action()
    {
        $this->view->pick('error/show404');
    }

    public function show500Action()
    {
        $this->view->pick('error/show500');
    }

}