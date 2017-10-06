<?php
namespace cmsgears\payment\common\components;

// CMG Imports
use cmsgears\payment\common\config\PaymentGlobal;

class MessageSource extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		PaymentGlobal::FIELD_AMOUNT => 'Amount',
		PaymentGlobal::FIELD_CURRENCY => 'Currency',
		// Transactions
		PaymentGlobal::FIELD_TXN_CODE => 'Code',
		PaymentGlobal::FIELD_TXN_TYPE => 'Type',
		PaymentGlobal::FIELD_TXN_MODE => 'Mode'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}
