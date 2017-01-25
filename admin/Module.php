<?php
namespace cmsgears\payment\admin;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\payment\common\config\PaymentGlobal;

class Module extends \cmsgears\core\common\base\Module {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $controllerNamespace = 'cmsgears\payment\admin\controllers';

	public $config				= [ PaymentGlobal::CONFIG_PAYMENT ];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-payment/admin/views' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

	public function getSidebarHtml() {

		$path	= Yii::getAlias( '@cmsgears' ) . '/module-payment/admin/views/sidebar.php';

		return $path;
	}
}
