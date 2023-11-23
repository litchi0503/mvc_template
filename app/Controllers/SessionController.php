<?php

namespace App\Controllers;

use App\Models\AppUser;

class SessionController extends CoreController
{

	/**
	 * Action pour afficher le formulaire de connexion
	 *
	 * @return void
	 */
	public function login()
	{
		$this->show('session/login');
	}

	/**
	 * Action pour gérer l'authentification d'un utilisateur
	 *
	 * @return void
	 */
	public function authenticate()
	{
		// 1. Récupérer et valider les infos POST 

		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');

		$isOk = true;
		$errorMessages = [];
		if(false == $email) {
			// email invalide
			$errorMessages[] = "L'email est invalide";
			$isOk = false;
		}
		if(empty($password)) {
			// mdp invalide
			$errorMessages[] = "Mot de passe vide";
			$isOk = false;
		}
		
		if(false == $isOk) {
			http_response_code(401);
			$data = ['errorMessages' => $errorMessages];
			$this->show('session/login', $data);
			// Arrete la fonction ici !
			return ;
		}

		// 2. Rechercher l'utilisateur avec son email

		$user = AppUser::findByEmail($email);

		// Est-ce que l'utilisateur existe dans la BDD ?

		if(false == $user) {
			// L'utisation n'existe pas dans la BDD
			http_response_code(401);
			$errorMessages[] = "Login ou mot de passe incorecte";
			$data = ['errorMessages' => $errorMessages];
			$this->show('session/login', $data);
			// Arrete la fonction ici !
			return ;
		}

		// 3. Comparer mdp de l'utilisateur

		if(!password_verify($password, $user->getPassword())) {
			// Le mdp n'est pas bon
			http_response_code(401);
			$errorMessages[] = "Login ou mot de passe incorecte";
			$data = ['errorMessages' => $errorMessages];
			$this->show('session/login', $data);
			// Arrete la fonction ici !
			return ;
		}

		// 4. Le mot de passe est bon
		// Enregistre l'utilisateur en SESSIONS

		$_SESSION['user'] = $user;
		$this->redirect('main-home');
	}

	public function logout() {
		// Supprime la variable de session 'user'
		unset($_SESSION['user']);
		$this->redirect('session-login');
	}
}
