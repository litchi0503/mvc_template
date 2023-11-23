<?php

namespace App\Models;

use App\Utils\Database;

use PDO;

class Truck extends CoreModel
{
    /**
     * @var string
     */
    private $brand;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $pods;


    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($id)
    {

    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     *
     * @return Category[]
     */
    public static function findAll(): array
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `trucks`';
        $pdoStatement = $pdo->query($sql);
        // self::class retourne 'App\Models\Category'
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        dump($results);
        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     *
     * @return Category[]
     */
    public function findAllHomepage()
    {
        
    }

    /**
     * Méthode permettant d'ajouter un enregistrement dans la table category
     * L'objet courant doit contenir toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     *
     * @return bool
     */
    public function insert(): bool
    {
        return false;
    }

    public function update(): bool
    {
        return false;
    }

    /**
     * Remove a Category from BDD
     *
     * @return void
     */
    public function delete(): bool
    {
        return false;
    }
    /**
     * Get the value of brand
     *
     * @return  string
     */ 
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @param  string  $brand
     *
     * @return  self
     */ 
    public function setBrand(string $brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return  string
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */ 
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of pods
     *
     * @return  string
     */ 
    public function getPods()
    {
        return $this->pods;
    }

    /**
     * Set the value of pods
     *
     * @param  string  $pods
     *
     * @return  self
     */ 
    public function setPods(string $pods)
    {
        $this->pods = $pods;

        return $this;
    }
    }

   