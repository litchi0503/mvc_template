<?php

// POINT D'ENTRÉE UNIQUE :
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require_once '../vendor/autoload.php';

session_start();

/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
	// Alors on définit le basePath d'AltoRouter
	$router->setBasePath($_SERVER['BASE_URI']);
	// ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
} else { // sinon
	// On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
	$_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter,
// afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"

/* ---------------
---    HOME    ---
----------------*/

$router->map(
	'GET',
	'/',
	[
		'method' => 'home',
		'controller' => '\App\Controllers\MainController' // On indique le FQCN de la classe
	],
	'main-home'
);

/* ---------------
--- TRUCK ---
----------------*/

$router->map(
	'GET',
	'/truck/list',
	[
		'method' => 'list',
		'controller' => '\App\Controllers\TruckController' // On indique le FQCN de la classe
	],
	'truck-list'
);


/* -------------
--- USER ---
--------------*/

$router->map(
	'GET',
	'/user/list',
	[
		'controller' => '\App\Controllers\AppUserController', // On indique le FQCN de la classe
		'method' => 'list'
	],
	'user-list'
);

$router->map(
	'GET',
	'/user/add',
	[
		'controller' => '\App\Controllers\AppUserController', // On indique le FQCN de la classe
		'method' => 'add'
	],
	'user-add'
);

$router->map(
	'POST',
	'/user/add',
	[
		'controller' => '\App\Controllers\AppUserController', // On indique le FQCN de la classe
		'method' => 'create'
	],
	'user-create'
);

/* -------------
--- SESSIONS ---
--------------*/

$router->map(
	'GET',
	'/session/login',
	[
		'controller' => '\App\Controllers\SessionController', // On indique le FQCN de la classe
		'method' => 'login'
	],
	'session-login'
);

$router->map(
	'POST',
	'/session/login',
	[
		'controller' => '\App\Controllers\SessionController', // On indique le FQCN de la classe
		'method' => 'authenticate'
	],
	'session-authenticate'
);

$router->map(
	'GET',
	'/session/logout',
	[
		'controller' => '\App\Controllers\SessionController', // On indique le FQCN de la classe
		'method' => 'logout'
	],
	'session-logout'
);

/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404

$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

$dispatcher->setControllersArguments($match['name']);

// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();
