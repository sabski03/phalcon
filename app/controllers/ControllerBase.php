<?php
declare(strict_types=1);

namespace PSA\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;


// ControllerBase
// This is the base controller for all controllers in the application


/**
 * @property Auth auth
 * @property Acl acl
 */
class ControllerBase extends Controller
{
    /**
     * @param Dispatcher $dispatcher
     *
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher): bool
    {
        $controllerName = $dispatcher->getControllerName();

        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {
            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->flash->notice('You don\'t have access to this module: private');

                $dispatcher->forward([
                    'controller' => 'index',
                    'action' => 'index',
                ]);
                return false;
            }

            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();

            if (!$this->acl->isAllowed($identity['roles'], $controllerName, $actionName)) {
                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['roles'], $controllerName, 'index')) {
                    $dispatcher->forward([
                        'controller' => $controllerName,
                        'action' => 'index',
                    ]);
                } else {
                    $dispatcher->forward([
                        'controller' => 'user_control',
                        'action' => 'index',
                    ]);
                }

                return false;
            }
        }

        return true;
    }
}
