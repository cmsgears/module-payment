<?php
namespace cmsgears\payment\common\services\interfaces\entities;

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
