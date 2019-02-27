<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\components;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\base\Component;

/**
 * The Payment Factory component initialise the services available in Payment Module.
 *
 * @since 1.0.0
 */
class Factory extends Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerResourceServices();
	}

	public function registerServiceAlias() {

		$this->registerResourceAliases();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\payment\common\services\interfaces\resources\ITransactionService', 'cmsgears\payment\common\services\resources\TransactionService' );
	}

	/**
	 * Registers resource aliases.
	 */
	public function registerResourceAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'transactionService', 'cmsgears\payment\common\services\resources\TransactionService' );
	}

}
