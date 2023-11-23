<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class User extends CoreModel
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $email;


    public static function findByEmail(string $email)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = 'SELECT *
            FROM app_user
            WHERE email = :email';

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $query = $pdo->prepare($sql);

        $query->bindValue(':email', $email);

        $query->execute();
        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $query->fetchObject(self::class);

        return $result;
    }

    public static function find(int $id): User
    {
        // To Do: Not implemented yet!
        return new User();
    }

    public static function findAll(): array
    {
        // To Do: Not implemented yet!
        return [];
    }

    public function insert(): bool
    {
        // To Do: Not implemented yet!
        return false;
    }

    public function update(): bool
    {
        // To Do: Not implemented yet!
        return false;
    }

    public function delete(): bool
    {
        // To Do: Not implemented yet!
        return false;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
