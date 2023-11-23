<?php

namespace App\Controllers;

abstract class CoreController
{

	/**
	 * =======================
	 * === Tableau des ACL ===
	 * =======================
	 * 
	 * Clé du tableau : les routes du site
	 * Valeur du tableau : liste des roles authorisés
	 * 
	 * !! Attention !! Les routes qui ne sont pas déclarée dans les ACL ne sont pas protégées !
	 */
	private const ACL = [
		// User
		'user-list' => ['admin'],
		'user-add' => ['admin'],
		'user-create' => ['admin'],
		
		'truck-list' => ['admin'],
	];

	/**
	 * Tableau pour la liste des actions POST à protéger par un token
	 * 
	 * !! Atention !! Les routes qui ne sont pas déclarée dans le tableau ne sont pas protégées !
	 *
	 * @param [type] $requestedRoute
	 */
	private const TOKEN_CSRF_POST = [

		// etc.
	];

	/**
	 * Tableau pour la liste des actions GET à protéger par un token
	 *
	 * !! Atention !! Les routes qui ne sont pas déclarée dans le tableau ne sont pas protégées !
	 * 
	 * @param [type] $requestedRoute
	 */
	private const TOKEN_CSRF_GET = [
		// etc.
	];

	public function __construct($requestedRoute)
	{
		// Est-ce que la route demandée est dans les clé des ACL
		if (array_key_exists($requestedRoute, self::ACL)) {
			$authorizedRoles = self::ACL[$requestedRoute];
			$this->checkAuthorization($authorizedRoles);
		}

		// Est ce que la page demandée doit avoir un token en POST
		if (in_array($requestedRoute, self::TOKEN_CSRF_POST)) {
			$this->checkTokenCSRF($_POST['tokenCSRF']);
		} else {
			// Est ce que la page demandée doit avoir un token en GET
			if (in_array($requestedRoute, self::TOKEN_CSRF_GET)) {
				$this->checkTokenCSRF($_GET['tokenCSRF']);
			}
		}
	}

	/**
	 * Génère un nouveau tokenCSRF puis l'enregistre en SESSION et reourne sa valeur
	 *
	 * @return string
	 */
	protected function generateTokenCSRF(): string
	{
		$tokenCSRF = bin2hex(random_bytes(32)); // génère un nombre aléatoire
		$_SESSION['tokenCSRF'] = $tokenCSRF;
		return $tokenCSRF;
	}

	/**
	 * Compare le tokenCSRF enregistré en SESSION
	 * avec le $tokenCSRF passé en arguement.
	 * Si les token ne correspondent pas, on renvois vers la page 403
	 *
	 * @param [type] $token
	 * @return void
	 */
	private function checkTokenCSRF($tokenCSRF)
	{
		if (isset($tokenCSRF) && isset($_SESSION['tokenCSRF']) && $tokenCSRF == $_SESSION['tokenCSRF']) {
			// J'ai reçu un token valide
			unset($_SESSION['tokenCSRF']);
			return true;
		}

		// Le token est invalide
		$this->err403();
	}

	/** 
	 * Vérifie si l'utilisateur est connecté ET a les droits suffisants
	 * La fonction compare les $authorizedRoles passés en argument avec le role de
	 * l'utilisateur connecté. Si l'utilisateur possède le bon role : return true.
	 * Sinon, rediection vers une page d'erreur.
	 * 
	 * Exemple : checkAuthorization(['admin']) -> si l'utilisateur est connecté, mais 
	 * n'a pas le role admin, alors redirection vers une page d'erreur
	 * 
	 * @param array $authorizedRoles roles necessaires pour effectuer une action
	 */
	private function checkAuthorization(array $authorizedRoles = []): bool
	{

		// 1. Est-ce que l'utilisateur est connecté ?

		if (!isset($_SESSION['user'])) {
			// Utilisateur non connecté
			$this->redirect('session-login');
		}

		// L'utilisateur est connecté
		// 2. Est-ce que l'utilisateur a les droits suffisants ?

		$user = $_SESSION['user'];
		$userRole = $user->getRole();

		if (in_array($userRole, $authorizedRoles)) {
			// L'utisateur a les droits suffisants
			return true;
		}

		// L'utilisateur n'a pas les droits -> affiche la page d'erreur 403
		$this->err403();
	}

	/**
	 * Redirige vers la page d'erreur 403 et quitte le programme
	 *
	 * @return void
	 */
	private function err403()
	{
		http_response_code(403);
		$this->show('error/err403');
		// Quitte le programme pour empécher le reste de l'action de s'exécuter
		exit();
	}

	/**
	 * Méthode permettant d'afficher du code HTML en se basant sur les views
	 *
	 * @param string $viewName Nom du fichier de vue
	 * @param array $viewData Tableau des données à transmettre aux vues
	 * @return void
	 */
	protected function show(string $viewName, $viewData = [])
	{
		// On globalise $router car on ne sait pas faire mieux pour l'instant
		global $router;

		// Comme $viewData est déclarée comme paramètre de la méthode show()
		// les vues y ont accès
		// ici une valeur dont on a besoin sur TOUTES les vues
		// donc on la définit dans show()
		$viewData['currentPage'] = $viewName;

		// définir l'url absolue pour nos assets
		$viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
		// définir l'url absolue pour la racine du site
		// /!\ != racine projet, ici on parle du répertoire public/
		$viewData['baseUri'] = $_SERVER['BASE_URI'];

		// On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
		// La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
		extract($viewData);
		// => la variable $currentPage existe désormais, et sa valeur est $viewName
		// => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
		// => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
		// => il en va de même pour chaque élément du tableau

		// $viewData est disponible dans chaque fichier de vue
		require_once __DIR__ . '/../Views/layout/header.tpl.php';
		require_once __DIR__ . '/../Views/' . $viewName . '.tpl.php';
		require_once __DIR__ . '/../Views/layout/footer.tpl.php';
	}

	/**
	 * Redirige vers une page 
	 *
	 * @param [type] $route identifiant de la route AltoRouter pour la redirection
	 * @param array $params optionels pour l'URL de la route
	 * @return void
	 */
	protected function redirect($route, $params = [])
	{
		global $router;
		$url = $router->generate($route, $params);
		header('Location: ' . $url);
		exit();
	}
}
