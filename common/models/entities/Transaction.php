<?php
namespace cmsgears\payment\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\payment\common\models\base\PaymentTables;

use cmsgears\core\common\models\traits\DataTrait;
use cmsgears\core\common\models\traits\ResourceTrait;

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
 * @property integer $amount
 * @property integer $currency
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class Transaction extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

    const MODE_OFFLINE		= 'offline';	// Direct - In hand

    const MODE_CARD         = 'card';		// Any card
    const MODE_DEBIT_C      = 'd-card';		// Specific for Debit Cards
    const MODE_CREDIT_C     = 'c-card';		// Specific for Credit Cards

	// Special offline
    const MODE_CHEQUE       = 'cheque';
    const MODE_DRAFT        = 'draft';

	// Direct Transfers
    const MODE_WIRE         = 'wire';

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
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
        	[ [ 'parentId', 'parentType', 'type' ], 'required' ],
        	[ [ 'id', 'content', 'data' ], 'safe' ],
        	[ 'currency', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
            [ [ 'parentType', 'type', 'mode', 'code', 'service' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'title' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ [ 'description' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'amount' ], 'number', 'min' => 0 ],
            [ [ 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
        	[ [ 'createdAt', 'updatedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        if ( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
        	'type' => Yii::$app ->cartMessage->getMessage( CartGlobal::FIELD_TXN_TYPE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Transaction ---------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return PaymentTables::TABLE_PAYMENT;
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
