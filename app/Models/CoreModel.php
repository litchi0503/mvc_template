<?php

namespace App\Models;

use App\Utils\Database;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
abstract class CoreModel
{
	/**
	 * @var int
	 */
	protected $id;
	/**
	 * @var string
	 */
	protected $created_at;
	/**
	 * @var string
	 */
	protected $updated_at;

	public abstract static function findAll(): array; 	// READ
	public abstract static function find(int $id); 		// READ
	public abstract function insert(): bool; // CREATE
	public abstract function update(): bool; // UPDATE
	public abstract function delete(): bool; // DELETE

	/**
	 * Enregistre une nouvelle instance dans la BDD ou met à jour 
	 * un enregistrement existant
	 *
	 * @return boolean true si la modification s'est bien déroulée, faux sinon
	 */
	public function save(): bool
	{
		// $id est defini
		if (isset($this->id)) {
			// modification de l'enregistrement
			return $this->update();
		} else {
			// $id n'est pas defini
			// il faut enregistrer une nouvelle entrée dans la BDD
			return $this->insert();
		}
	}

	/**
	 * Enregistre une nouvelle instance dans la BDD ou met à jour 
	 * un enregistrement existant
	 * 
	 * Version complète de save()
	 *
	 * @return boolean true si la modification s'est bien déroulée, faux sinon
	 */
	public function securedSave(): bool
	{
		$pdo = Database::getPDO();
		// Démarrer une transaction
		$pdo->beginTransaction();

		$isOk = false;

		// $id est defini & l'objet existe en BDD
		// static:: pour utiliser l'implementation de find de la classe de l'objet appellant
		if (isset($this->id) && false != static::find($this->id)) {
			// modification de l'enregistrement
			$isOk = $this->update();
		} else {
			// $id n'est pas defini et $objectModel est faux (l'objet n'existe pas en base)
			// il faut enregistrer une nouvelle entrée dans la BDD
			$isOk = $this->insert();
		}
		// Si insert ou update s'est bien passé
		if ($isOk) {
			// "push" les modifications
			$pdo->commit();
		} else {
			// retour en arrière, avant la transaction
			$pdo->rollBack();
		}

		return $isOk;
	}

	/**
	 * Get the value of id
	 *
	 * @return  int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Get the value of created_at
	 *
	 * @return  string
	 */
	public function getCreatedAt(): string
	{
		return $this->created_at;
	}

	/**
	 * Get the value of updated_at
	 *
	 * @return  string
	 */
	public function getUpdatedAt(): string
	{
		return $this->updated_at;
	}
}
