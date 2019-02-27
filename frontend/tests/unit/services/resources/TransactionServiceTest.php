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
	
	public function testCreetByParamsGetByParentGetPageSuccessDeclineFailedFunction() {

		$this->transactionService = Yii::$app->factory->get('transactionService');

		//model class
		$this->modelClass	= $this->transactionService->getModelClass();
		$this->siteId		= Yii::$app->core->getSiteId();
		$this->parentId		= rand(1, 999);
		$this->parentType	= 'parentType';		
		
		$this->specify('test create by params',
		function() {

			$type		= $this->modelClass::TYPE_CREDIT;
			$amount		= rand(1, 999);

			$model = $this->transactionService->getModelObject();

			expect( 'model validation passed', $model->validate() )->false();
			expect( 'model parentId error not found', $model->errors['parentId'][0] )->contains( 'Parent cannot be blank');
			expect( 'model parentType error not found', $model->errors['parentType'][0] )->contains( 'Parent Type cannot be blank');
			expect( 'model type error not found', $model->errors['type'][0] )->contains( 'Type cannot be blank');

			$model->parentId	= $this->parentId;
			$model->parentType	= $this->parentType;
			$model->type		= $type; 			

			expect( 'model validation passed', $model->validate() )->true();

			$result = $this->transactionService->createByParams( [ 'parentId' => $this->parentId, 'parentType' => $this->parentType, 'userId' => rand(1, 999),
				'amount' => $amount, 'currency' => 'USD', 'type' => $type, 'mode' => $this->modelClass::MODE_ONLINE, 
				'title' => 'Cashback Commision', 'createdBy' => 1 ], [ 'siteId' => $this->siteId ] );

			expect( 'model not created', isset( $result->id ) )->true();

			$findModel = $this->transactionService->getById( $result->id );

			expect( 'parentType not matched', $findModel->parentType )->same( $this->parentType );
			expect( 'parentId not matched', $findModel->parentId )->same( $this->parentId );
			expect( 'type not same', $findModel->isCredit() )->true();
			expect( 'status is not new', $findModel->status )->same( $this->modelClass::STATUS_NEW );
		});
		
		
		$this->specify('test get page by parent id and parent type',
		function() {
			
			$dataProvider = $this->transactionService->getPageByParent( $this->parentId, $this->parentType );
			$models = $dataProvider->getModels();
			
			foreach( $models as $model ) {

				expect('parentId not matched', $model->parentId )->same( $this->parentId );
				expect('parentType not matched', $model->parentType )->same( $this->parentType );
				expect('siteId not matched', $model->siteId )->same( $this->siteId );
			}
		});
		
		$this->specify('test get page function',
		function() {
			
			$dataProvider	= $this->transactionService->getPage( );
			$models			= $dataProvider->getModels();
			
			foreach( $models as $model ) {

				expect('parentId not matched', $model->parentId )->notNull();
				expect('parentId not matched', $model->type )->notNull();
				expect('parentType not matched', $model->parentType )->notNull();
				expect('siteId not matched', $model->siteId )->same( $this->siteId );
			}
		});

		$this->specify('test failed function',
		function() {
			
			$dataProvider	= $this->transactionService->getPage( );
			$models			= $dataProvider->getModels();
			
			foreach( $models as $model ) {

				$this->transactionService->failed( $model );
				$updatedModel = $this->transactionService->getById($model->id);
		
				expect( 'model status is not updated failed', $updatedModel->isFailed() )->true();
				expect('parentId not matched', $model->parentId )->notNull();
				expect('parentId not matched', $model->type )->notNull();
				expect('parentType not matched', $model->parentType )->notNull();
				expect('siteId not matched', $model->siteId )->same( $this->siteId );
			}
		});

		$this->specify('test success function',
		function() {
			
			$dataProvider	= $this->transactionService->getPage();
			$models			= $dataProvider->getModels();
			
			foreach( $models as $model ) {

				$this->transactionService->success( $model );

				$updatedModel = $this->transactionService->getById($model->id);

				expect( 'model status is not updated', $updatedModel->isSuccess() )->true();
				expect('parentId not matched', $model->parentId )->notNull();
				expect('parentId not matched', $model->type )->notNull();
				expect('parentType not matched', $model->parentType )->notNull();
				expect('siteId not matched', $model->siteId )->same( $this->siteId );
			}
		});

		$this->specify('test decline function',
		function() {
			
			$dataProvider	= $this->transactionService->getPage();
			$models			= $dataProvider->getModels();
			
			foreach( $models as $model ) {

				$this->transactionService->declined( $model );

				$updatedModel = $this->transactionService->getById($model->id);

				expect( 'model status is not updated', $updatedModel->isDeclined() )->true();
				expect('parentId not matched', $model->parentId )->notNull();
				expect('parentId not matched', $model->type )->notNull();
				expect('parentType not matched', $model->parentType )->notNull();
				expect('siteId not matched', $model->siteId )->same( $this->siteId );
			}
		});
	}
}
