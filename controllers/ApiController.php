<?php

namespace app\controllers;

use app\models\Currency;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;


class ApiController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];
        return $behaviors;
    }

    public function actionCurrencies($page=1,$limit=10){
        if(\Yii::$app->request->get('page')){
            $page = \Yii::$app->request->get('page');
        }
        $page = intval($page) == 1 ? 0 : $page * $limit;

        return ArrayHelper::map(Currency::find()->limit($limit)->offset($page)->all(),'name','rate');
    }

    public function actionCurrency($id){
        if(!$id){
            return "Id Parameter is required";
        }

        return Currency::findOne($id);
    }


}
