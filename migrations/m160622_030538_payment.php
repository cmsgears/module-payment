<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\base\Migration;

/**
 * The payment migration inserts the database tables of form module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m160622_030538_payment extends Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

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

		$this->createTable( $this->prefix . 'payment_transaction', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'type' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'mode' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'refund' => $this->boolean()->notNull()->defaultValue( false ),
			'code' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'service' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'amount' => $this->double(),
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
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_creator', $this->prefix . 'payment_transaction', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_modifier', $this->prefix . 'payment_transaction', 'modifiedBy' );
	}

	private function generateForeignKeys() {

		// Transaction
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

		// transaction
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_creator', $this->prefix . 'payment_transaction' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'transaction_modifier', $this->prefix . 'payment_transaction' );
	}

}
