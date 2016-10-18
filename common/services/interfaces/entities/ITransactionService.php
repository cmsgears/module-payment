<?php
namespace cmsgears\payment\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ITransactionService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

	public function getPageByCreatorId( $creatorId );

	public function getPageByParent( $parentId, $parentType );

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------
}
