<?php

$router = $di->getRouter();

// Define your routes here

$router->add('/signup', [
    'controller' => 'index',
    'action'     => 'signup',
]);

$router->add('/forgotPassword', [
    'controller' => 'index',
    'action'     => 'forgotPassword',
]);

$router->add('/logout', [
    'controller' => 'index',
    'action'     => 'logout',
]);

$router->add('/profile', [
    'controller' => 'user_control',
    'action'     => 'index',
]);

$router->add('/changePassword', [
    'controller' => 'user_control',
    'action'     => 'changePassword',
]);

$router->handle($_SERVER['REQUEST_URI']);
