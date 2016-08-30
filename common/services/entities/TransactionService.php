<?php
namespace cmsgears\payment\common\services\entities;

// Yii Imports
use \Yii;

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

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return $modelClass::findById( $id );
	}

	// Data Provider ------

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Payment(), $config );
	}

	// Create -----------

	public function createTransaction( $config = [] ) {

		$user			= Yii::$app->core->getAppUser();
		$data			= isset( $config[ 'data' ] ) ? $config[ 'data' ] : null;
		$processedAt	= isset( $config[ 'processedAt' ] ) ? $config[ 'processedAt' ] : null;

		$transaction				= new Transaction();
        $transaction->parentId      = $config[ 'parentId' ];
        $transaction->parentType    = $config[ 'parentType' ];
		$transaction->createdBy		= $user->id;
		$transaction->type			= $config[ 'type' ];
		$transaction->mode			= $config[ 'mode' ];
		$transaction->amount		= $config[ 'amount' ];
		$transaction->description	= $config[ 'description' ];
		$transaction->currency		= $config[ 'currency' ];
		$transaction->processedAt	= $processedAt;
        $transaction->data          = $data;

		$transaction->save();

		// Return Transaction
		return $transaction;
	}

	// Update -----------

    public static function updateTransactionType( $transaction, $type ) {

        $transaction->type  = $type;
        $transaction->update();
    }

}
