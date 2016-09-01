<?php
namespace cmsgears\payment\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ITransactionService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    public function getPayments( $user = false );

	public function getDatePayments();

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	public function createTransaction( $config = [] );

	// Update -------------

	// Delete -------------

}
