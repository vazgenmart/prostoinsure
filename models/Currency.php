<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Currency".
 *
 * @property int $id
 * @property string $name
 * @property double $rate
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'rate'], 'required'],
            [['rate'], 'number'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'rate' => 'Rate',
        ];
    }
}
