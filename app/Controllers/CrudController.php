<?php

namespace App\Controllers;

use App\Controllers\CoreController;

/**
 * Class abstraite dédiée aux actions du CRUD
 */
abstract class CrudController extends CoreController {

	/**
	 * READ : get all element in table
	 *
	 * @return void
	 */
	abstract function list();
	/**
	 * Action to send empty new form
	 *
	 * @return void
	 */
	abstract function add();
	/**
	 * Add new element in table
	 *
	 * @return void
	 */
	abstract function create();
	/**
	 * Action to send filled form to update
	 *
	 * @param integer $id
	 * @return void
	 */
	abstract function update(int $id);
	/**
	 * Update one element in table
	 *
	 * @param integer $id
	 * @return void
	 */
	abstract function edit(int $id);
	/**
	 * Remove one element in table
	 *
	 * @param integer $id
	 * @return void
	 */
	abstract function delete(int $id);
}