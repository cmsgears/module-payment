<?php

class m160622_030642_payment_index extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix	= 'cmg_';
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Transaction
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_title', $this->prefix . 'transaction', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_type', $this->prefix . 'transaction', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_mode', $this->prefix . 'transaction', 'mode' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_code', $this->prefix . 'transaction', 'code' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_service', $this->prefix . 'transaction', 'service' );
		$this->createIndex( 'idx_' . $this->prefix . 'transaction_currency', $this->prefix . 'transaction', 'currency' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Transaction
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_title', $this->prefix . 'transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_type', $this->prefix . 'transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_mode', $this->prefix . 'transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_code', $this->prefix . 'transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_service', $this->prefix . 'transaction' );
		$this->dropIndex( 'idx_' . $this->prefix . 'transaction_currency', $this->prefix . 'transaction' );
	}
}