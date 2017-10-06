<?php
namespace cmsgears\payment\common\config;

class PaymentGlobal {

	// Traits - Metas, Tags, Attachments, Addresses --------------------

	const TYPE_PAYMENT				= 'payment';
	const TYPE_TRANSACTION			= 'transaction';

	// Permissions -----------------------------------------------------

	// Config ----------------------------------------------------------

	const CONFIG_PAYMENT			= 'payment';

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_AMOUNT				= 'amountField';
	const FIELD_CURRENCY			= 'currencyField';

	// Transactions
	const FIELD_TXN_CODE			= 'txnCodeField';
	const FIELD_TXN_TYPE			= 'txnTypeField';
	const FIELD_TXN_MODE			= 'txnModeField';
}
