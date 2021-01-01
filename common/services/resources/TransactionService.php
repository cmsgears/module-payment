<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\payment\common\config\PaymentGlobal;

use cmsgears\payment\common\models\resources\Transaction;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\payment\common\services\interfaces\resources\ITransactionService;

use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * TransactionService provide service methods of transaction model.
 *
 * @since 1.0.0
 */
class TransactionService extends \cmsgears\core\common\services\base\ModelResourceService implements ITransactionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\payment\common\models\resources\Transaction';

	public static $parentType = PaymentGlobal::TYPE_TRANSACTION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService = $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TransactionService --------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
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
			'defaultOrder' => $defaultSort
		]);

		// Sort -------------

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$mode	= Yii::$app->request->getQueryParam( 'mode' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Status
		if( isset( $status ) && empty( $config[ 'conditions' ][ "$modelTable.status" ] ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ] = $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Mode
		if( isset( $mode ) && empty( $config[ 'conditions' ][ "$modelTable.mode" ] ) && isset( $modelClass::$urlRevModeMap[ $mode ] ) ) {

			$config[ 'conditions' ][ "$modelTable.mode" ] = $modelClass::$urlRevModeMap[ $mode ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'credit': {

					$config[ 'conditions' ][ "$modelTable.type" ] = $modelClass::TYPE_CREDIT;

					break;
				}
				case 'debit': {

					$config[ 'conditions' ][ "$modelTable.type" ] = $modelClass::TYPE_DEBIT;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions' ][] = [ "$modelTable.userId" => $userId ];

		return $this->getPage( $config );
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
 		$link			= isset( $params[ 'link' ] ) ? $params[ 'link' ] : null;
 		$userId			= isset( $params[ 'userId' ] ) ? $params[ 'userId' ] : null;

		$model = isset( $config[ 'model' ] ) ? $config[ 'model' ] : new static::$modelClass;

		// Mandatory
		$model->parentId	= $params[ 'parentId' ];
		$model->parentType	= $params[ 'parentType' ];
		$model->status		= $status;
		$model->type		= $params[ 'type' ];
		$model->mode		= $params[ 'mode' ];
		$model->amount		= $params[ 'amount' ];
		$model->currency	= $params[ 'currency' ];
		$model->title		= $params[ 'title' ];

		// Optional
		$model->description	= $desc;
		$model->code		= $code;
		$model->processedAt	= $processedAt;
		$model->link		= $link;
		$model->userId		= $userId;

		$model->save();

		// Return Transaction
		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'title', 'description', 'mode', 'code', 'service', 'link'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'type', 'status'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function cancel( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_CANCELLED );
	}

	public function fail( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_FAILED );
	}

	public function pending( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_PENDING );
	}

	public function decline( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_DECLINED );
	}

	public function reject( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_REJECTED );
	}

	public function success( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_SUCCESS );
	}

	public function approve( $model, $config = [] ) {

		return $this->updateStatus( $model, Transaction::STATUS_SUCCESS );
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Delete files
			$this->fileService->deleteFiles( [ $model->files ] );

			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'cancel': {

						$this->cancel( $model );

						break;
					}
					case 'fail': {

						$this->fail( $model );

						break;
					}
					case 'pending': {

						$this->pending( $model );

						break;
					}
					case 'decline': {

						$this->decline( $model );

						break;
					}
					case 'reject': {

						$this->reject( $model );

						break;
					}
					case 'success': {

						$this->success( $model );

						break;
					}
					case 'approve': {

						$this->approve( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

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
