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

class PaymentProperties extends \cmsgears\core\common\config\Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	/**
	 * The property to find whether payments are enabled for the site.
	 */
	const PROP_ACTIVE = 'active';

	/**
	 * The property currencies in CSV format to store the available currencies.
	 */
	const PROP_CURRENCIES = 'currencies';

	/**
	 * The default currency among the currencies.
	 */
    const PROP_CURRENCY = 'currency';

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
	public function isActive() {

		return $this->properties[ self::PROP_ACTIVE ];
	}

	/**
	 * Returns the currencies CSV.
	 */
	public function getCurrencies() {

		return $this->properties[ self::PROP_CURRENCIES ];
	}

	/**
	 * Returns the default currency of the application.
	 *
	 * @return string
	 */
	public function getDefaultCurrency() {

		return $this->properties[ self::PROP_CURRENCY ];
	}

}
