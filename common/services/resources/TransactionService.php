<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\services\resources;

// CMG Imports
use Yii;
use yii\data\Sort;
use cmsgears\payment\common\config\PaymentGlobal;

use cmsgears\payment\common\models\resources\Transaction;

use cmsgears\payment\common\services\interfaces\resources\ITransactionService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * TransactionService provide service methods of transaction model.
 *
 * @since 1.0.0
 */
class TransactionService extends ResourceService implements ITransactionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\payment\common\models\resources\Transaction';

	public static $parentType	= PaymentGlobal::TYPE_TRANSACTION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TransactionService --------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'mode' => [
					'asc' => [ "$modelTable.mode" => SORT_ASC ],
					'desc' => [ "$modelTable.mode" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Mode'
				],
				'service' => [
					'asc' => [ "$modelTable.service" => SORT_ASC ],
					'desc' => [ "$modelTable.service" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Service'
				],
				'amount' => [
					'asc' => [ "$modelTable.amount" => SORT_ASC ],
					'desc' => [ "$modelTable.amount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Amount'
				],
				'currency' => [
					'asc' => [ "$modelTable.currency" => SORT_ASC ],
					'desc' => [ "$modelTable.currency" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Currency'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
				'pdate' => [
					'asc' => [ "$modelTable.processedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.processedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Processed At'
				]
			],
			'defaultOrder' => [ 'cdate' => SORT_DESC ]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		// Filters ----------

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByCreatorId( $creatorId ) {

		$modelTable = $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.createdBy" => $creatorId ] ] );
	}

	public function getPageByParent( $parentId, $parentType ) {

		$modelTable = $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createByParams( $params = [], $config = [] ) {

		$status			= isset( $params[ 'status' ] ) ? $params[ 'status' ] : Transaction::STATUS_NEW;
		$desc			= isset( $params[ 'description' ] ) ? $params[ 'description' ] : null;
		$code			= isset( $params[ 'code' ] ) ? $params[ 'code' ] : null;
		$processedAt	= isset( $params[ 'processedAt' ] ) ? $params[ 'processedAt' ] : null;
 		$data			= isset( $params[ 'data' ] ) ? $params[ 'data' ] : null;

		$transaction	= isset( $config[ 'transaction' ] ) ? $config[ 'transaction' ] : new Transaction();
 		$link			= isset( $params[ 'link' ] ) ? $params[ 'link' ] : null;
 		$userId			= isset( $params[ 'userId' ] ) ? $params[ 'userId' ] : null;

		// This condition is applies when we detach authorBehavior from transaction model, so in this case we need to set createdBy manually
		if( isset( $params[ 'createdBy' ] ) ) {

			$transaction->createdBy	= $params[ 'createdBy' ];
		}

		$modelClass	= new static::$modelClass;

		$ignoreSite	= $config[ 'ignoreSite' ] ?? false;

		if( $modelClass::isMultiSite() && !$ignoreSite ) {

			$transaction->siteId	= $config[ 'siteId' ] ?? Yii::$app->core->siteId;
		}

		// Mandatory
		$transaction->parentId		= $params[ 'parentId' ];
		$transaction->parentType	= $params[ 'parentType' ];
		$transaction->status		= $status;
		$transaction->type			= $params[ 'type' ];
		$transaction->mode			= $params[ 'mode' ];
		$transaction->amount		= $params[ 'amount' ];
		$transaction->currency		= $params[ 'currency' ];
		$transaction->title			= $params[ 'title' ];

		// Optional
		$transaction->description	= $desc;
		$transaction->code			= $code;
		$transaction->processedAt	= $processedAt;
		$transaction->data			= $data;
		$transaction->link			= $link;
		$transaction->userId		= $userId;

		$transaction->save();

		// Return Transaction
		return $transaction;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'description', 'mode', 'code', 'service', 'link' ]
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status	= $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function failed( $model ) {

		return $this->updateStatus( $model, Transaction::STATUS_FAILED );
	}

	public function declined( $model ) {

		return $this->updateStatus( $model, Transaction::STATUS_DECLINED );
	}

	public function success( $model ) {

		return $this->updateStatus( $model, Transaction::STATUS_SUCCESS );
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// TransactionService --------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
