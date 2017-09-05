<?php

class m160622_030642_payment_index extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Transaction
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_title', $this->prefix . 'payment_transaction', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_type', $this->prefix . 'payment_transaction', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_mode', $this->prefix . 'payment_transaction', 'mode' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_code', $this->prefix . 'payment_transaction', 'code' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_service', $this->prefix . 'payment_transaction', 'service' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_currency', $this->prefix . 'payment_transaction', 'currency' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Transaction
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_title', $this->prefix . 'payment_transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_type', $this->prefix . 'payment_transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_mode', $this->prefix . 'payment_transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_code', $this->prefix . 'payment_transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_service', $this->prefix . 'payment_transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_currency', $this->prefix . 'payment_transaction' );
	}
}
