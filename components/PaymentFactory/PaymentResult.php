<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/19/15 AD
 * Time: 10:47 PM
 */

namespace app\components\PaymentFactory;

class PaymentResult extends \yii\db\ActiveRecord
{

    public $status;
    public $message;
    public $payby;


    public static function tableName()
    {
        return 'payment_result';
    }
}