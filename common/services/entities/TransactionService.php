<?php
namespace cmsgears\payment\common\services\entities;

// CMG Imports
use cmsgears\payment\common\models\base\PaymentTables;
use cmsgears\payment\common\models\entities\Transaction;

use cmsgears\payment\common\services\interfaces\entities\ITransactionService;

class TransactionService extends \cmsgears\core\common\services\base\EntityService implements ITransactionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\payment\common\models\entities\Transaction';

	public static $modelTable	= PaymentTables::TABLE_TRANSACTION;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TransactionService --------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= self::$modelClass;
		$modelTable = self::$modelTable;

		$sort = new Sort([
			'attributes' => [
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'mode' => [
					'asc' => [ "$modelTable.mode" => SORT_ASC ],
					'desc' => [ "$modelTable.mode" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Mode'
				],
				'service' => [
					'asc' => [ "$modelTable.service" => SORT_ASC ],
					'desc' => [ "$modelTable.service" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Service'
				],
				'amount' => [
					'asc' => [ "$modelTable.amount" => SORT_ASC ],
					'desc' => [ "$modelTable.amount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Amount'
				],
				'currency' => [
					'asc' => [ "$modelTable.currency" => SORT_ASC ],
					'desc' => [ "$modelTable.currency" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Currency'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
				'pdate' => [
					'asc' => [ "$modelTable.processedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.processedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Processed At'
				]
			],
			'defaultOrder' => [ 'cdate' => SORT_DESC ]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		return parent::getPage( $config );
	}

	public function getPageByCreatorId( $creatorId ) {

		$modelTable = self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.createdBy" => $creatorId ] ] );
	}

	public function getPageByParent( $parentId, $parentType ) {

		$modelTable = self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByParams( $params = [], $config = [] ) {

		$desc			= isset( $params[ 'description' ] ) ? $params[ 'description' ] : null;
		$code			= isset( $params[ 'code' ] ) ? $params[ 'code' ] : null;
		$processedAt	= isset( $params[ 'processedAt' ] ) ? $params[ 'processedAt' ] : null;
 		$data			= isset( $params[ 'data' ] ) ? $params[ 'data' ] : null;

		$transaction	= isset( $config[ 'transaction' ] ) ? $config[ 'transaction' ] : new Transaction();

		// This condition is applies when we detach authorBehavior from transaction model, so in this case we need to set createdBy manually
		if( isset( $params[ 'createdBy' ] ) ) {

			$transaction->createdBy	= $params[ 'createdBy' ];
		}

		// Mandatory
		$transaction->parentId		= $params[ 'parentId' ];
		$transaction->parentType	= $params[ 'parentType' ];
		$transaction->type			= $params[ 'type' ];
		$transaction->mode			= $params[ 'mode' ];
		$transaction->amount		= $params[ 'amount' ];
		$transaction->currency		= $params[ 'currency' ];
		$transaction->title			= $params[ 'title' ];

		// Optional
		$transaction->description	= $desc;
		$transaction->code			= $code;
		$transaction->processedAt	= $processedAt;
		$transaction->data			= $data;

		$transaction->save();

		// Return Transaction
		return $transaction;
	}

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// TransactionService --------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
