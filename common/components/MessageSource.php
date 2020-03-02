<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\components;

// CMG Imports
use cmsgears\payment\common\config\PaymentGlobal;

class MessageSource extends \cmsgears\core\common\base\MessageSource {

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
		PaymentGlobal::FIELD_TXN_MODE => 'Mode',
		PaymentGlobal::FIELD_REFUND => 'Refund'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

}
