<?php
namespace cmsgears\payment\common\services\entities;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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

	// Read ---------------

	// Read - Models ---

	public function getPayments( $user = false ) {

		$modelClass	= self::$modelClass;

		$payments	= $modelClass::queryByPayment();

		if( $user ) {

			$user	= Yii::$app->user->getIdentity();

			return $payments->where( 'createdBy=:creator', [ ':creator' => $user->id ] )->orderBy( 'createdAt DESC' )->all();
		}

		return $payments->orderBy( 'createdAt DESC' )->all();
	}

	public function getDatePayments() {

		$query	= new Query();

		$query->select( 'DATE_FORMAT(createdAt, "%m-%Y")' )
		->from( PaymentTables::TABLE_TRANSACTION );


		$query	= $query->createCommand();

		$modelClass	= self::$modelClass;

		$payments	= $modelClass::queryByPayment();

		$payments	= $payments->groupBy( 'processedAt' )->all();

		return $payments;
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createTransaction( $config = [] ) {

		$user			= Yii::$app->core->getAppUser();
 		$data			= isset( $config[ 'data' ] ) ? $config[ 'data' ] : null;
 		$processedAt	= isset( $config[ 'processedAt' ] ) ? $config[ 'processedAt' ] : null;
 		$creator		= isset( $config[ 'createdBy' ] ) ? $config[ 'createdBy' ] : $user->id;
		$code			= isset( $config[ 'code' ] ) ? $config[ 'code' ] : null;

		$transaction				= new Transaction();
		$transaction->parentId		= $config[ 'parentId' ];
		$transaction->parentType	= $config[ 'parentType' ];
		$transaction->createdBy		= $creator;
		$transaction->type			= $config[ 'type' ];
		$transaction->mode			= $config[ 'mode' ];
		$transaction->amount		= $config[ 'amount' ];
		$transaction->description	= $config[ 'description' ];
		$transaction->currency		= $config[ 'currency' ];
		$transaction->processedAt	= $processedAt;
		$transaction->data			= $data;

		$transaction->save();

		// Return Transaction
		return $transaction;
	}

	// Update -------------

	public function updateTransactionType( $transaction, $type ) {

		$transaction->type	= $type;

		$transaction->update();
	}

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
