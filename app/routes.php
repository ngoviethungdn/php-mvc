<?php

$router->add('', ['controller' => 'WorkController', 'action' => 'index']);

$router->add('works/list', ['controller' => 'WorkController', 'action' => 'list']);

$router->add('works/create', ['controller' => 'WorkController', 'action' => 'create']);

$router->add('works/{id}/update', ['controller' => 'WorkController', 'action' => 'update']);

$router->add('works/{id}/delete', ['controller' => 'WorkController', 'action' => 'delete']);
