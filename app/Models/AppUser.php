<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel
{
	private $email;
	private $password;
	private $firstname;
	private $lastname;
	private $role;
	private $status;

	public static function find(int $id)
	{
		// se connecter à la BDD
		$pdo = Database::getPDO();

		// écrire notre requête
		$sql = 'SELECT * FROM `app_user` WHERE `id` =' . $id;

		// exécuter notre requête
		$pdoStatement = $pdo->query($sql);

		// un seul résultat => fetchObject
		// self::class retourne 'App\Models\AppUser'
		$user = $pdoStatement->fetchObject(self::class);

		// retourner le résultat
		return $user;
	}

	public static function findByEmail(string $email)
	{
		// se connecter à la BDD
		$pdo = Database::getPDO();

		// écrire notre requête
		$sql = 'SELECT * FROM `app_user` WHERE `email` = :email';

		// exécuter notre requête
		$query = $pdo->prepare($sql);

		$query->bindValue(':email', $email);

		$query->execute();

		// un seul résultat => fetchObject
		// self::class retourne 'App\Models\AppUser'
		$user = $query->fetchObject(self::class);

		// retourner le résultat
		return $user;
	}

	public static function findAll(): array
	{
		$pdo = Database::getPDO();

		$sql = 'SELECT * FROM `app_user`';

		$pdoStatement = $pdo->query($sql);

		// self::class retourne 'App\Models\AppUser'
		$results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

		return $results;
	}

	public function insert(): bool
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête INSERT INTO
		$sql = "INSERT INTO `app_user` (email, password, firstname, lastname, role, status)
            VALUES (:email, :password, :firstname, :lastname, :role, :status)";

		// Préparation de la quete INSERT
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':email', $this->email, PDO::PARAM_STR);
		$query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
		$query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
		// Calcul le hash du mot de passe
		$passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
		// Enregistre le hash du mot de passe
		$query->bindValue(':password', $passwordHash, PDO::PARAM_STR);
		$query->bindValue(':role', $this->role, PDO::PARAM_STR);
		$query->bindValue(':status', $this->status, PDO::PARAM_INT);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$insertedRow = $query->execute();

		// Si au moins une ligne ajoutée
		if ($insertedRow) {
			// Alors on récupère l'id auto-incrémenté généré par MySQL
			$this->id = $pdo->lastInsertId();

			// On retourne VRAI car l'ajout a parfaitement fonctionné
			return true;
			// => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
		}

		// Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
		return false;
	}

	public function update(): bool
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE INTO
		$sql = "UPDATE `app_user`
            SET email = :email, 
                password = :password, 
                firstname = :firstname,
                lastname = :lastname, 
                role = :role, 
                status = :status, 
                updated_at = NOW()
            WHERE id = :id";

		// Préparation de la quete UPDATE
		$query = $pdo->prepare($sql);

		// String / replace des mots clés dans $sql par les valeurs à insérer
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->bindValue(':email', $this->email, PDO::PARAM_STR);
		$query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
		$query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
		// Calcul le hash du mot de passe
		$passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
		// Enregistre le hash du mot de passe
		$query->bindValue(':password', $passwordHash, PDO::PARAM_STR);
		$query->bindValue(':role', $this->role, PDO::PARAM_STR);
		$query->bindValue(':status', $this->status, PDO::PARAM_INT);

		// Execute la requete preparee
		// S'il y a des requete SQL dans les donnees a inserer
		// elles ne seront pas executee !!
		$isUpdated = $query->execute();

		return $isUpdated;
	}

	public function delete(): bool
	{
		// Récupération de l'objet PDO représentant la connexion à la DB
		$pdo = Database::getPDO();

		// Ecriture de la requête UPDATE
		$sql = 'DELETE FROM `app_user`
					WHERE id = :id';

		// On aurait pu faire un query / exec car $id est déjà filtré par alto router 
		$pdoStatement = $pdo->prepare($sql);

		$pdoStatement->bindValue(':id', $this->id);

		$pdoStatement->execute();

		// On retourne VRAI, une ligne a été supprimée
		return ($pdoStatement->rowCount() == 0);
	}

	/**
	 * Get the value of email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 *
	 * @return  self
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get the value of password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @return  self
	 */
	public function setPassword($password): bool
	{
		// Mot de passe d'au moins 8 char
		if(strlen($password) < 8) {
			return false;
		}

		$superRegex = "$^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[_\-\|\&\*=\@\$])[A-Za-z\d_\-\|\&\*=\@\$]{8,}
		^(?=.*[\p{Ll}])(?=.*[\p{Lu}])(?=.*\d)(?=.*[_\-\|\&\*=\@\$])[\p{Lu}\p{Ll}\d_\-\|\&\*=\@\$]{8,}";
		// if (!preg_match($superRegex, $password)) {
		// 	return false;
		// }

		if (!preg_match("#[0-9]+#", $password)) {
			return false;
		}
	
		if (!preg_match("#[a-zA-Z]+#", $password)) {
			return false;
		}   

		$this->password = $password;
		return true;
	}

	/**
	 * Get the value of firstname
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Set the value of firstname
	 *
	 * @return  self
	 */
	public function setFirstname($firstname)
	{
		if(empty($firstname)) {
			return false;
		}

		$this->firstname = $firstname;
		return true;
	}

	/**
	 * Get the value of lastname
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Set the value of lastname
	 *
	 * @return  self
	 */
	public function setLastname($lastname)
	{
		if(empty($lastname)) {
			return false;
		}

		$this->lastname = $lastname;
		return true;
	}

	/**
	 * Get the value of role
	 */
	public function getRole()
	{
		return $this->role;
	}

	/**
	 * Set the value of role
	 *
	 * @return  self
	 */
	public function setRole($role)
	{
		if($role != 'admin' && $role != 'catalog-manager') {
			return false;
		}

		$this->role = $role;
		return true;
	}

	/**
	 * Get the value of status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set the value of status
	 *
	 * @return  self
	 */
	public function setStatus($status)
	{
		if($status != 1 && $status != 2) {
			return false;
		}

		$this->status = $status;
		return $this;
	}
}
