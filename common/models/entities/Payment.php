<?php
namespace cmsgears\payment\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\payment\common\models\entities\PaymentTables;

use cmsgears\core\common\models\base\CmgEntity;

/**
 * Payment Entity - The primary class.
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $description
 * @property string $type
 * @property string $mode
 * @property integer $amount
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $data
 */
class Payment extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    const MODE_FREE         = 'free';
    const MODE_CARD         = 'card';
    const MODE_DEBIT_C      = 'debit card';
    const MODE_CREDIT_C     = 'credit card';
    const MODE_CHEQUE       = 'cheque';
    const MODE_DRAFT        = 'draft';
    const MODE_WIRE         = 'wire';

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

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

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        $trim = [];

        if (Yii::$app -> cmgCore -> trimFieldValue) {

            $trim[] = [['description'], 'filter', 'filter' => 'trim', 'skipOnArray' => true];
        }

        $rules = [[['parentId', 'parentType', 'type'], 'required'], [['id', 'description', 'mode', 'data'], 'safe'], [['createdAt'], 'date', 'format' => Yii::$app -> formatter -> datetimeFormat]];

        if (Yii::$app -> cmgCore -> trimFieldValue) {

            return ArrayHelper::merge($trim, $rules);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return ['type' => Yii::$app -> cmgCartMessage -> getMessage(CartGlobal::FIELD_TXN_TYPE)];
    }

    // Payment --------------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return PaymentTables::TABLE_PAYMENT;
    }

    // Payment --------------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>