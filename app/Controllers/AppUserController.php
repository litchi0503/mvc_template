<?php

namespace App\Controllers;

use App\Models\AppUser;
use Symfony\Component\VarDumper\Caster\Caster;

class AppUserController extends CrudController
{
	// Instance de Category à manipuler dans tout le controller
	private AppUser $user;
	// Liste des messages d'erreur
	private array $errorMessages = [];

	/**
	 * Récupère et vérifie les données saisie en POST
	 * Si les données sont valides, elles sont enregistrées dans $this->category
	 *
	 * @return boolean vrai si les données sont valides, faux sinon
	 */
	private function getDataFromPost(): bool
	{
		// Récupérer et netoyer les donnes en POST
		$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
		$role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
		
		// /* === Vérifier les données saisies === */
		$isOk = true;
		// First name
		if (!$this->user->setFirstname($firstname)) {
			$isOk = false;
			$this->errorMessages[] = 'Nom invalide';
		}
		// Last name
		if (!$this->user->setLastname($lastname)) {
			$isOk = false;
			$this->errorMessages[] = 'Prénom invalide';
		}
		// Email
		if (false == $email) {
			$isOk = false;
			$this->errorMessages[] = 'Email invalide';
		} else {
			$this->user->setEmail($email);
		}
		// Password
		if (!$this->user->setPassword($password)) {
			$isOk = false;
			$this->errorMessages[] = 'Mot de passe invalide, au moins 8 caractère et 1 chiffre et 1 lettre';
		} 
		// Role
		if (!$this->user->setRole($role)) {
			$isOk = false;
			$this->errorMessages[] = 'Role invalide';
		}
		// Status
		if (false == $status || !$this->user->setStatus($status)) {
			$isOk = false;
			$this->errorMessages[] = 'Status invalide';
		}

		return $isOk;
	}

	/**
	 * Affiche le formulaire des catégories avec les messages d'erreur 
	 * et les données en POST s'il y en a.
	 * Le programme s'arrete à la fin de la fonction.
	 *
	 * @param integer $errorCode le code d'erreur à renvoyer au navigateur
	 * @return void
	 */
	private function returnErrorForm(int $errorCode)
	{
		// Enregistre les données en POST s'il y en a
		isset($_POST['firstname']) ?: $this->user->setFirstname($_POST['firstname']);
		isset($_POST['lastname']) ?: $this->user->setLastname($_POST['lastname']);
		isset($_POST['email']) ?: $this->user->setEmail($_POST['email']);
		isset($_POST['role']) ?: $this->user->setRole($_POST['role']);
		isset($_POST['status']) ?: $this->user->setStatus($_POST['status']);

		// Les données à envoyer au formulaire
		$data = [
			'title' => "Erreur",
			'user' => $this->user,
			'errorMessages' => $this->errorMessages,
		];

		// Code d'erreur à envoyer au navigateur
		http_response_code($errorCode);
		$this->show('app-user/add-update', $data);
		// On arrete le code ici !
		exit();
	}

	/**
	 * Action afficher la liste des catégorie
	 *
	 * @return void
	 */
	public function list()
	{
		// @see ACL 
		// $this->checkAuthorization(['admin']);

		$userList = AppUser::findAll();

		$data = [
			'userList' => $userList,
		];

		$this->show('app-user/list', $data);
	}

	/**
	 * Action afficher le formulaire d'ajout d'une catgéorie
	 *
	 * @return void
	 */
	public function add()
	{
		// @see ACL 
		// $this->checkAuthorization(['admin']);
		
		$data = [
			'title' => 'Ajouter un utilisateur',
			'user' => new AppUser(),
		];
		
		$this->show('app-user/add-update', $data);
	}

	/**
	 * Action enrgistrer une nouvelle catégorie
	 *
	 * @return void
	 */
	public function create()
	{
		// @see ACL 
		// $this->checkAuthorization(['admin']);

		/* === Récupérer les données en POST === */
		$this->user = new AppUser();

		// Get data from form
		if (false == $this->getDataFromPost()) {
			$this->returnErrorForm(400);
		}

		/* === Les données saisies sont valides === */

		/* === Insérer les infos dans la BDD === */

		if (false == $this->user->save()) {
			$this->errorMessages[] = "Impossible d'enregistrer l'utilisateur'";
			$this->returnErrorForm(400);
		}

		// Redirection vers la liste des catégories
		$this->redirect('user-list');
	}

	/**
	 * Action d'afficher un formulaire pré-rempli pour
	 * la modification d'une catégorie
	 *
	 * @param integer $id de la catégorie à afficher dans le formulaire
	 * 
	 * @return void
	 */
	public function update(int $idCategory)
	{
		$this->category = Category::find($idCategory);

		if (false === $this->category) {
			$this->errorMessages[] = "La catégorie à modifier n'existe plus";
			$this->returnErrorForm(404);
		}

		$data = [
			'title' => 'Modifier une catégorie',
			'category' => $this->category,
		];

		$this->show('category/add-update', $data);
	}

	/**
	 * Méthode pour mettre à jour une catégorie
	 */
	public function edit($idCategory)
	{
		// ### 1. Retrouver la catégorie à modifier dans la BDD

		$this->category = Category::find($idCategory);

		if (false === $this->category) {
			$this->errorMessages[] = "La catégorie à modifier n'existe plus";
			$this->returnErrorForm(404);
		}

		// ### 2. Contrôler les infos qui viennent du formulaire

		if (!$this->getDataFromPost()) {
			$this->returnErrorForm(400);
		}

		// === 3. Faire la MAJ dans la BDD

		if (false == $this->category->save()) {
			$this->errorMessages[] = "Impossible de modifier la catégorie";
			$this->returnErrorForm(400);
		}

		// MAJ OK
		// Redirect After Post
		$this->redirect('category-list');
	}

	function delete(int $id)
	{
		$this->category = Category::find($id);

		if (false == $this->category) {
			http_response_code(404);
			$this->show('category-list');
		}

		$this->category->delete();
		$this->redirect('category-list');
	}
}
