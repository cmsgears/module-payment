<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class m160622_030538_payment extends \yii\db\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix		= 'cmg_';

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Payment
		$this->upPayment();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upPayment() {

		$this->createTable( $this->prefix . 'transaction', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( CoreGlobal::TEXT_MEDIUM ),
			'title' => $this->string( CoreGlobal::TEXT_LARGE )->notNull(),
			'description' => $this->string( CoreGlobal::TEXT_XLARGE )->defaultValue( null ),
			'type' => $this->string( CoreGlobal::TEXT_MEDIUM )->notNull(),
			'mode' => $this->string( CoreGlobal::TEXT_MEDIUM )->defaultValue( null ),
			'code' => $this->string( CoreGlobal::TEXT_MEDIUM )->defaultValue( null ),
			'service' => $this->string( CoreGlobal::TEXT_MEDIUM )->defaultValue( null ),
			'amount' => $this->double( 2 ),
			'currency' => $this->string( CoreGlobal::TEXT_SMALL )->notNull(),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text(),
			'processedAt' => $this->date()->defaultValue( null )
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_creator', $this->prefix . 'transaction', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_modifier', $this->prefix . 'transaction', 'modifiedBy' );
	}

	private function generateForeignKeys() {

		// Transaction
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_creator', $this->prefix . 'transaction', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_modifier', $this->prefix . 'transaction', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'transaction' );
	}

	private function dropForeignKeys() {

		// transaction
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_creator', $this->prefix . 'transaction' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_modifier', $this->prefix . 'transaction' );
	}
}

?>