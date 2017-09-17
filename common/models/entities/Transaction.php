<?php
namespace cmsgears\payment\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\payment\common\config\PaymentGlobal;

use cmsgears\payment\common\models\base\PaymentTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Transaction Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $mode
 * @property string $code
 * @property string $service
 * @property integer $status
 * @property integer $amount
 * @property string $currency
 * @property string $link
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property date $processedAt
 * @property string $content
 * @property string $data
 */
class Transaction extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Transaction Modes

	const MODE_OFFLINE		= 'offline';	// Direct - In hand
	const MODE_FREE 		= 'free'; 		// Free

	const MODE_CARD			= 'card';		// Any card
	const MODE_DEBIT_C		= 'd-card';		// Specific for Debit Cards
	const MODE_CREDIT_C		= 'c-card';		// Specific for Credit Cards

	// Special offline
	const MODE_CHEQUE		= 'cheque';
	const MODE_DRAFT		= 'draft';

	// Direct Transfers
	const MODE_WIRE			= 'wire';

	// Transaction Types

	const TYPE_CREDIT		= 'credit';
	const TYPE_DEBIT		= 'debit';
	const TYPE_REFUND		= 'refund';

	// Transaction Status

	const STATUS_NEW		=   0;
	const STATUS_FAILED		=  50;
	const STATUS_DECLINED	= 100;
	const STATUS_SUCCESS	= 500;

	// Public -----------------

	public static $modeMap = [
		self::MODE_OFFLINE => 'Offline',
		self::MODE_FREE => 'Free',
		self::MODE_CARD => 'Card',
		self::MODE_DEBIT_C => 'Debit Card',
		self::MODE_CREDIT_C => 'Credit Card',
		self::MODE_CHEQUE => 'Cheque',
		self::MODE_DRAFT => 'Draft'
	];

	public static $typeMap = [
		self::TYPE_CREDIT => 'Credit',
		self::TYPE_DEBIT => 'Debit',
		self::TYPE_REFUND => 'Refund'
	];

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_FAILED => 'Failed',
		self::STATUS_DECLINED => 'Declined',
		self::STATUS_SUCCESS => 'Success'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= PaymentGlobal::TYPE_TRANSACTION;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use CreateModifyTrait;
	use DataTrait;
	use FileTrait;
	use ResourceTrait;

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
				'class' => AuthorBehavior::className()
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
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

		$rules = [
			// Required, Safe
			[ [ 'parentId', 'parentType', 'type' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Text Limit
			[ 'currency', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'parentType', 'type', 'mode', 'code', 'service' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'link', 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ 'amount', 'number', 'min' => 0 ],
			[ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'processedAt' ], 'date', 'type' => 'datetime' ]
		];

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
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->transactionMessage->getMessage( PaymentGlobal::FIELD_TXN_TYPE ),
			'mode' => Yii::$app->transactionMessage->getMessage( PaymentGlobal::FIELD_TXN_MODE ),
			'code' => Yii::$app->transactionMessage->getMessage( PaymentGlobal::FIELD_TXN_CODE ),
			'service' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SERVICE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'amount' => Yii::$app->transactionMessage->getMessage( PaymentGlobal::FIELD_AMOUNT ),
			'currency' => Yii::$app->transactionMessage->getMessage( PaymentGlobal::FIELD_CURRENCY ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Transaction ---------------------------

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isFailed() {

		return $this->status == self::STATUS_FAILED;
	}

	public function isDeclined() {

		return $this->status == self::STATUS_DECLINED;
	}

	public function isSuccess() {

		return $this->status == self::STATUS_SUCCESS;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return PaymentTables::TABLE_TRANSACTION;
	}

	// CMG parent classes --------------------

	// Transaction ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	public static function findByCodeService( $code, $service ) {

		return self::find()->where( 'code=:code AND service=:service', [ ':code' => $code, ':service' => $service ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
