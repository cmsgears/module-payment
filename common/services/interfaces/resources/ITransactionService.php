<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

/**
 * ITransactionService declares methods specific to transaction model.
 *
 * @since 1.0.0
 */
interface ITransactionService extends IModelResourceService {

	// Data Provider ------

	// Read ---------------

	public function getPageByUserId( $userId, $config = [] );

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function cancel( $model, $config = [] );

	public function fail( $model, $config = [] );

	public function pending( $model, $config = [] );

	public function decline( $model, $config = [] );

	public function reject( $model, $config = [] );

	public function success( $model, $config = [] );

	public function approve( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
