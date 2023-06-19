<?php
declare(strict_types=1);

namespace PSA\Controllers;


use Phalcon\Http\Response;
use Phalcon\Tag;
use PSA\Models\Users;

class DashboardController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
    }

    //view all the active paused close status
    public function indexAction()
    {
        $this->tag->setTitle('Dashboard');

        $users = Users::find();

        $count = count($users);

        $this->view->count = $count;



    }
}
