<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The payment migration inserts the database tables of form module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m161001_030538_payment extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk		= Yii::$app->migration->isFk();
		$this->options	= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Transaction
		$this->upTransaction();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upTransaction() {

		$this->createTable( $this->prefix . 'payment_transaction', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->defaultValue( null ),
			'docId' => $this->bigInteger( 20 )->defaultValue( null ),
			'createdBy' => $this->bigInteger( 20 )->defaultValue( null ),
			'modifiedBy' => $this->bigInteger( 20 )->defaultValue( null ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'type' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'mode' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'refund' => $this->boolean()->notNull()->defaultValue( false ),
			'code' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'service' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'amount' => $this->double()->notNull(),
			'currency' => $this->string( Yii::$app->core->smallText )->notNull(),
			'link' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'processedAt' => $this->dateTime()->defaultValue( null ),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_site', $this->prefix . 'payment_transaction', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_user', $this->prefix . 'payment_transaction', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_doc', $this->prefix . 'payment_transaction', 'docId' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_creator', $this->prefix . 'payment_transaction', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_modifier', $this->prefix . 'payment_transaction', 'modifiedBy' );
	}

	private function generateForeignKeys() {

		// Transaction
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_site', $this->prefix . 'payment_transaction', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_user', $this->prefix . 'payment_transaction', 'userId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_doc', $this->prefix . 'payment_transaction', 'docId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_creator', $this->prefix . 'payment_transaction', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'transaction_modifier', $this->prefix . 'payment_transaction', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'payment_transaction' );
	}

	private function dropForeignKeys() {

		// Transaction
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_site', $this->prefix . 'payment_transaction' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_user', $this->prefix . 'payment_transaction' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_doc', $this->prefix . 'payment_transaction' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_creator', $this->prefix . 'payment_transaction' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_modifier', $this->prefix . 'payment_transaction' );
	}

}
