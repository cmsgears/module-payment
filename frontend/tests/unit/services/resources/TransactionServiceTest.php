<?php

namespace cmsgears\payment\frontend\tests\unit\services\resources;

use Yii;
//use common\fixtures\UserFixture;
//use common\models\entities\CashbackActivity;
/**
 * Login form test
 */
class TransactionserviceTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;
	
	public function testHello(){
		
		expect( "hello", true )->true();
	}
	
	public function testCreetByParams() {

		$this->transactionService = Yii::$app->factory->get('transactionService');

		//model class
		$this->modelClass	= $this->transactionService->getModelClass();
		$this->siteId		= Yii::$app->core->siteId;

		$this->specify('test create by params',
		function() {

			$parentId	= rand(1, 999);
			$parentType = 'parentType';
			$type		= $this->modelClass::TYPE_CREDIT;
			$amount		= rand(1, 999);

			$model = $this->transactionService->getModelObject();

			expect( 'model validation passed', $model->validate() )->false();
			expect( 'model parentId error not found', $model->errors['parentId'][0] )->contains( 'Parent cannot be blank');
			expect( 'model parentType error not found', $model->errors['parentType'][0] )->contains( 'Parent Type cannot be blank');
			expect( 'model type error not found', $model->errors['type'][0] )->contains( 'Type cannot be blank');

			$model->parentId	= $parentId;
			$model->parentType	= $parentType;
			$model->type		= $type; 			

			expect( 'model validation passed', $model->validate() )->true();

			$result = $this->transactionService->createByParams( [ 'parentId' => $parentId, 'parentType' => $parentType, 'userId' => rand(1, 999),
				'amount' => $amount, 'currency' => 'USD', 'type' => $type, 'mode' => $this->modelClass::MODE_ONLINE, 
				'title' => 'Cashback Commision', 'createdBy' => 1 ], [ 'siteId' => $this->siteId ] );

			expect( 'model not created', isset( $result->id ) )->true();

			$findModel = $this->transactionService->getById( $result->id );

			expect( 'parentType not matched', $findModel->parentType )->same( $parentType );
			expect( 'parentId not matched', $findModel->parentId )->same( $parentId );
			expect( 'type not same', $findModel->type )->same( $type );
			expect( 'status is not new', $findModel->status )->same( $this->modelClass::STATUS_NEW );
		});
	}
}
