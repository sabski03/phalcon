<?php
declare(strict_types=1);

use Phalcon\Config;

return new Config([
    'privateResources' => [
        'dashboard' => ['index'],
        'changeHistory' => ['index'],
        'allUsers' => ['index', 'newUser', 'viewUsers', 'tasks', 'edit', 'delete', 'createTasks'],
        'users' => ['index', 'create', 'edit', 'delete', 'authorization'],
        'roles' => ['index', 'create', 'edit', 'delete', 'editPermission', 'employee'],
        'news' => ['index','news' , 'addPost'],
        'transferred' => [ 'index', 'users',],
        'tasks' => ['index', 'manage', ],
    ]
]);