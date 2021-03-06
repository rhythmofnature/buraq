<?php

namespace app\modules\business\controllers;

use Yii;
use app\modules\business\models\CustomerDetails;
use app\modules\business\models\CustomerDetailsSearch;
use app\modules\business\models\MaterialTypes;
use app\modules\business\models\MaterialTypesSearch;
use app\modules\business\models\CustomerMaterialPrice;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * CustomerController implements the CRUD actions for CustomerDetails model.
 */
class CustomerController extends Controller
{
//     public function behaviors()
//     {
//         return [
//             'verbs' => [
//                 'class' => VerbFilter::className(),
//                 'actions' => [
//                     'delete' => ['post'],
//                 ],
//             ],
//         ];
//     }

    /**
     * Lists all CustomerDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomerDetails model.
     * @param integer $id
     * @return mixed
     */
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CustomerDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomerDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CustomerDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CustomerDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPrice(){
	  //echo $id= $_GET['id'];exit;
	  $searchModel = new MaterialTypesSearch();
	  $model = new CustomerDetails();
	  $customer_id = $_REQUEST['id'];
	  $searchModel->customer=$customer_id;
	  if(isset($_POST['price'])){
		
		
		$selected = $_POST['price'];
		foreach($selected as $key => $val){
		  $objMaterialPrice=new CustomerMaterialPrice();
		  $material_id=$key;
		  $material_price =$val;
		  
		  $count = CustomerMaterialPrice::find()->where(['customer_id'=>$customer_id,'material_id'=>$material_id])->count();
		  if($count>0){
		  $objMaterialPrice=CustomerMaterialPrice::findOne(['customer_id'=>$customer_id,'material_id'=>$material_id]);
		  }
		  $objMaterialPrice->customer_id =$customer_id;
		  $objMaterialPrice->material_id = $material_id;
		  $objMaterialPrice->price=$material_price;
		  $objMaterialPrice->status=1;
		  
		  $objMaterialPrice->save();
// 		  print_r($objMaterialPrice->getErrors());
// 		  echo "<pre>";print_r($objMaterialPrice->attributes);
// 		  exit;
		  
		  
		}
		
	  }
	  
	  $query = MaterialTypes::find();
    //$query->joinWith(['milkProduction']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
	  return $this->render('price', [
                'model' => $model,
                'dataProvider'=>$dataProvider,
                'searchModel'=>$searchModel
                
            ]);
    }
    
    public function actionGettripprice(){
	  
	  $material_id = $_POST['material'];
	  $quantity = $_POST['quantity'];
	  $customer_id = $_POST['customer'];
	  $customerMaterialPrice=CustomerMaterialPrice::findOne(['customer_id'=>$customer_id,'material_id'=>$material_id]);
	  $price = $customerMaterialPrice->price;
	  
	  echo $total_price = $price*$quantity;
    
    }


}
