<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\payment\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\payment\common\config\PaymentGlobal;

use cmsgears\payment\common\models\base\PaymentTables;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Transaction represents a financial transaction.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $description
 * @property integer $type
 * @property integer $mode
 * @property boolean $refund
 * @property string $code
 * @property string $service
 * @property integer $status
 * @property float $amount
 * @property string $currency
 * @property string $link
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property date $processedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Transaction extends \cmsgears\core\common\models\base\ModelResource implements IAuthor, IData, IFile, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Transaction Modes

	const MODE_OFFLINE		=   0;	// Direct - In hand or some other means
	const MODE_FREE 		= 100; 	// Free

	const MODE_ONLINE 		= 200; 	// Online - Net Banking
	const MODE_CARD			= 300;	// Any card
	const MODE_DEBIT_C		= 400;	// Specific for Debit Cards
	const MODE_CREDIT_C		= 500;	// Specific for Credit Cards

	// Special offline
	const MODE_CHEQUE		= 600;
	const MODE_DRAFT		= 700;

	// Direct Transfers
	const MODE_WIRE			= 800;	// Wired

	// Transaction Types

	const TYPE_CREDIT		=  0;
	const TYPE_DEBIT		= 10;

	// Transaction Status

	const STATUS_NEW		=   0;
	const STATUS_CANCELLED	=  50;
	const STATUS_FAILED		= 100;
	const STATUS_PENDING	= 150;
	const STATUS_DECLINED	= 200;
	const STATUS_REJECTED	= 250;
	const STATUS_SUCCESS	= 500;

	// Public -----------------

	public static $modeMap = [
		self::MODE_OFFLINE => 'Offline',
		self::MODE_FREE => 'Free',
		self::MODE_ONLINE => 'Online',
		self::MODE_CARD => 'Card',
		self::MODE_DEBIT_C => 'Debit Card',
		self::MODE_CREDIT_C => 'Credit Card',
		self::MODE_CHEQUE => 'Cheque',
		self::MODE_DRAFT => 'Draft'
	];

	public static $typeMap = [
		self::TYPE_CREDIT => 'Credit',
		self::TYPE_DEBIT => 'Debit'
	];

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_FAILED => 'Failed',
		self::STATUS_PENDING => 'Pending',
		self::STATUS_DECLINED => 'Declined',
		self::STATUS_REJECTED => 'Rejected',
		self::STATUS_SUCCESS => 'Success'
	];

	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'cancelled' => self::STATUS_CANCELLED,
		'failed' => self::STATUS_FAILED,
		'pending' => self::STATUS_PENDING,
		'declined' => self::STATUS_DECLINED,
		'rejected' => self::STATUS_REJECTED,
		'success' => self::STATUS_SUCCESS
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = PaymentGlobal::TYPE_TRANSACTION;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use FileTrait;
	use GridCacheTrait;
	use MultiSiteTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'authorBehavior' => [
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'parentId', 'parentType', 'type', 'amount' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache', 'siteId', 'service', 'userId' ], 'safe' ],
			// Text Limit
			[ 'currency', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'parentType', 'code', 'service' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'link', 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			[ 'refund', 'boolean' ],
			// Other
			[ 'amount', 'number', 'min' => 0 ],
			[ [ 'type', 'mode', 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'userId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'title', 'link', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_TXN_TYPE ),
			'mode' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_TXN_MODE ),
			'refund' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_TXN_MODE ),
			'code' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_TXN_CODE ),
			'service' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SERVICE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'amount' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_AMOUNT ),
			'currency' => Yii::$app->paymentMessage->getMessage( PaymentGlobal::FIELD_CURRENCY ),
			'link' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Transaction ---------------------------

	public function getUser(){

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Check whether transaction is new.
	 *
	 * @return boolean
	 */
	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	/**
	 * Check whether transaction is cancelled.
	 *
	 * @return boolean
	 */
	public function isCancelled() {

		return $this->status == self::STATUS_CANCELLED;
	}

	/**
	 * Check whether transaction is failed.
	 *
	 * @return boolean
	 */
	public function isFailed() {

		return $this->status == self::STATUS_FAILED;
	}

	/**
	 * Check whether transaction is failed.
	 *
	 * @return boolean
	 */
	public function isPending() {

		return $this->status == self::STATUS_PENDING;
	}

	/**
	 * Check whether transaction is declined.
	 *
	 * @return boolean
	 */
	public function isDeclined() {

		return $this->status == self::STATUS_DECLINED;
	}

	/**
	 * Check whether transaction is rejected.
	 *
	 * @return boolean
	 */
	public function isRejected() {

		return $this->status == self::STATUS_REJECTED;
	}

	/**
	 * Check whether transaction is successful.
	 *
	 * @return boolean
	 */
	public function isSuccess() {

		return $this->status == self::STATUS_SUCCESS;
	}

	/**
	 * Check whether transaction is approved. Alias of success status.
	 *
	 * @return boolean
	 */
	public function isApproved() {

		return $this->status == self::STATUS_SUCCESS;
	}

	public function isCredit() {

		return $this->type == self::TYPE_CREDIT;
	}

	public function isDebit() {

		return $this->type == self::TYPE_DEBIT;
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return PaymentTables::getTableName( PaymentTables::TABLE_TRANSACTION );
	}

	// CMG parent classes --------------------

	// Transaction ---------------------------

	// Read - Query -----------

	public static function queryByUserId( $userId ) {

		return static::find()->where( 'userId=:uid', [ ':uid' => $userId ] );
	}

	// Read - Find ------------

	public static function findByUserId( $userId ) {

		return self::queryByUserId( $userId )->all();
	}

	/**
	 * Find and return the transaction specific to given code and service.
	 *
	 * @param string $code
	 * @param string $service
	 * @return Transaction
	 */
	public static function findByCodeService( $code, $service ) {

		return self::find()->where( 'code=:code AND service=:service', [ ':code' => $code, ':service' => $service ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
