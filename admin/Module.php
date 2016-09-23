<?php
namespace cmsgears\payment\admin;

// Yii Imports
use \Yii;

// CMG Imports

class Module extends \cmsgears\core\common\base\Module {

	public $controllerNamespace = 'cmsgears\payment\admin\controllers';

	public $config				= [ ];

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-payment/admin/views' );
	}

	public function getSidebarHtml() {

		$path	= Yii::getAlias( "@cmsgears" ) . "/module-payment/admin/views/sidebar.php";

		return $path;
	}
}

?>