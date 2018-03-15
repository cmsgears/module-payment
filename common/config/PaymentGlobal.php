<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\config;

class PaymentGlobal {

	// System Sites ---------------------------------------------------

	// System Pages ---------------------------------------------------

	// Grouping by type ------------------------------------------------

	const TYPE_PAYMENT		= 'payment';
	const TYPE_TRANSACTION	= 'transaction';

	// Templates -------------------------------------------------------

	// Config ----------------------------------------------------------

	const CONFIG_PAYMENT	= 'payment';

	// Roles -----------------------------------------------------------

	// Permissions -----------------------------------------------------

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_AMOUNT		= 'amountField';
	const FIELD_CURRENCY	= 'currencyField';

	// Transactions
	const FIELD_TXN_CODE	= 'txnCodeField';
	const FIELD_TXN_TYPE	= 'txnTypeField';
	const FIELD_TXN_MODE	= 'txnModeField';

	const FIELD_REFUND		= 'refundField';

}
