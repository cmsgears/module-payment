<?php
namespace cmsgears\payment\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\payment\common\config\PaymentGlobal;

class PaymentProperties extends \cmsgears\core\common\config\CmgProperties {

	const PROP_PAYMENTS		= 'payments';
    const PROP_CURRENCY		= 'currency';

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new PaymentProperties();

			self::$instance->init( PaymentGlobal::CONFIG_PAYMENT );
		}

		return self::$instance;
	}

	public function isPayments() {

		$status = $this->properties[ self::PROP_PAYMENTS ];
	}

	public function getCurrency() {

		return $this->properties[ self::PROP_CURRENCY ];
	}
}
