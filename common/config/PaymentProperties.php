<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\config;

// CMG Imports
use cmsgears\payment\common\config\PaymentGlobal;

use cmsgears\core\common\config\Properties;

class PaymentProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const PROP_PAYMENTS		= 'payments';

    const PROP_CURRENCY		= 'currency';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

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

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PaymentProperties ---------------------

	/**
	 * Check whether payments are enabled. None of the payment systems will work if
	 * it is disabled.
	 *
	 * @return boolean
	 */
	public function isPayments() {

		return $this->properties[ self::PROP_PAYMENTS ];
	}

	/**
	 * Returns the default currency of the application.
	 *
	 * @return string
	 */
	public function getCurrency() {

		return $this->properties[ self::PROP_CURRENCY ];
	}

}
