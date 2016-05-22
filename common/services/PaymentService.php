<?php
namespace cmsgears\payment\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\payment\common\models\entities\Payment;

class PaymentService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Payment::findById( $id );
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

	public static function create( $parentId, $parentType, $type, $mode, $amount, $message, $data = null ) {

		// Set Attributes
		$user				    = Yii::$app->user->getIdentity();

		$payment				= new Payment();
        $payment->parentId      = $parentId;
        $payment->parentType    = $parentType;
		$payment->createdBy		= $user->id;
		$payment->type			= $type;
		$payment->mode			= $mode;
		$payment->amount		= $amount;
		$payment->description	= $message;
        $payment->data          = $data;

		$payment->save();

		// Return Payment
		return $payment;
	}

	// Update -----------

}

?>