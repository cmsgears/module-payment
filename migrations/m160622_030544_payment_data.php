<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\core\common\utilities\DateUtil;

class m160622_030544_payment_data extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;

		// Site config
		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

	public function up() {

		// Create various config
		$this->insertPaymentConfig();

		// Init default config
		$this->insertDefaultConfig();
	}

	private function insertPaymentConfig() {

		$this->insert( $this->prefix . 'core_form', [
				'siteId' => $this->site->id,
				'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
				'name' => 'Config Payment', 'slug' => 'config-payment',
				'type' => CoreGlobal::TYPE_SYSTEM,
				'description' => 'Payment configuration form.',
				'successMessage' => 'All configurations saved successfully.',
				'captcha' => false,
				'visibility' => Form::VISIBILITY_PROTECTED,
				'active' => true, 'userMail' => false,'adminMail' => false,
				'createdAt' => DateUtil::getDateTime(),
				'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlug( 'config-Payment', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'payments', 'Payments', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{"title":"Payments Enabled"}' ],
			[ $config->id, 'currency', 'Currency', FormField::TYPE_SELECT, false, 'required', 0, NULL, '{"title":"Currency","items":{"USD":"USD","CAD":"CAD"}}' ],
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'valueType', 'value' ];

		$metas	= [
				[ $this->site->id, 'payments', 'Payments', 'payment','flag', '0' ],
				[ $this->site->id, 'currency','Currency', 'payment','text', 'USD' ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	public function down() {

		echo "m160622_030544_payment_data will be deleted with m160621_014408_core and m160622_030538_payment.\n";

		return true;
	}
}
