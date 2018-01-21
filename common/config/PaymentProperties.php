<?php
namespace cmsgears\payment\common\config;

// CMG Imports
use cmsgears\payment\common\config\PaymentGlobal;

class PaymentProperties extends \cmsgears\core\common\config\CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	const PROP_PAYMENTS		= 'payments';

    const PROP_CURRENCY		= 'currency';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// PaymentProperties ---------------------

	// Singleton

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new PaymentProperties();

			self::$instance->init( PaymentGlobal::CONFIG_PAYMENT );
		}

		return self::$instance;
	}

	// Properties

	public function isPayments() {

		$status = $this->properties[ self::PROP_PAYMENTS ];
	}

	public function getCurrency() {

		return $this->properties[ self::PROP_CURRENCY ];
	}
}
