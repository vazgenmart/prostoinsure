<?php

namespace app\commands;


use yii\console\Controller;
use app\models\Currency;
use yii\db\Exception;
use yii\helpers\ArrayHelper;


class CurrencyScrapperController extends Controller
{

    public function actionIndex()
    {

        $uri = 'http://www.cbr.ru/scripts/XML_daily.asp';
        $data = file_get_contents($uri);
        $currencies = simplexml_load_string($data);
        $allData = Currency::find()->all();
        $models = [];

        // making array of objects it will incrise preformance,
        // I will not need create find query for each element of xml document
        foreach ($allData as $allDatum) {
            $models[$allDatum->id] = $allDatum;
        }

        // creating array from currencies dat for checking if currency exists
        $existsCurrencies = ArrayHelper::map($allData, 'id', 'name');

        // creating empty array for that dat which nust be inserted
        $new = [];

        foreach ($currencies as $currency) {

            if (($id = array_search($currency->Name, $existsCurrencies)) !== false) {
                $model = $models[$id];
                $model->name = (string)$currency->Name;
                $model->rate = floatval((str_replace(",",'.',$currency->Value)));
                $model->save();
            } else {
                $new[] = [(string)$currency->Name, floatval((str_replace(",",'.',$currency->Value)))];
            }
        }

        if (!empty($new)) {
            try {
                \Yii::$app->db->createCommand()->batchInsert(Currency::tableName(), ['name', 'rate'], $new)->execute();
            } catch (Exception $e) {
                var_dump($e->getMessage());die;
                return $e->getMessage();
            }
        }

    }
}
