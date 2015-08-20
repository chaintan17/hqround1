<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/19/15 AD
 * Time: 7:35 PM
 */

namespace app\models;

use app\components\PaymentFactory\PayerInfo;
use app\components\PaymentFactory\PaymentFactory;
use app\components\PaymentFactory\PaymentResult;
use yii\base\Model;

class PaymentForm extends Model
{
    public $price;
    public $currency;
    public $full_name;
    public $card_holder;
    public $card_number;
    public $card_expired_month;
    public $card_expired_year;
    public $card_ccv;
    public $braintree_nonce;

    public static $currenyList = [
        'USD' => 'USD',
        'EUR' => 'EUR',
        'THB' => 'THB',
        'HKD' => 'HKD',
        'SGD' => 'SGD',
        'AUD' => 'AUD',
    ];
    public function rules()
    {
        return [
            // username and password are both required
            [['price', 'currency', 'full_name', 'card_holder', 'card_number', 'card_expired_month', 'card_expired_year',
                'card_expired', 'card_ccv'], 'required'],
            [['price'], 'number'],
            [['card_number', 'card_expired_month', 'card_expired_year'], 'integer'],
            [['card_number'], 'string', 'length' => 16],
            [['card_expired_month'], 'string', 'length' => 2],
            [['card_expired_year'], 'string', 'length' => 4],
            [['braintree_nonce'], 'safe']
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute
     * `firstName`, we can declare a label `First Name` which is more user-friendly and can
     * be displayed to end users.
     *
     * By default an attribute label is generated using [[generateAttributeLabel()]].
     * This method allows you to explicitly specify attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to
     * merge the parent labels with child labels using functions such as `array_merge()`.
     *
     * @return array attribute labels (name => label)
     * @see generateAttributeLabel()
     */
    public function attributeLabels()
    {
        return [
            'card_holder' => 'Card Holder Name',
            'card_ccv' => 'Card CCV'
        ];
    }

    /**
     * @return PaymentResult|null
     */
    public function makePayment()
    {
        $paymentFactory = new PaymentFactory();

        $fullName = explode(" ", $this->full_name);
        $cardHolderName = explode(" ", $this->card_holder);

        // SET PAYER INFO
        $payerInfo = new PayerInfo();
        $payerInfo->setBuyerName($fullName[0], $fullName[1]);
        $payerInfo->setCardHolderName($cardHolderName[0], $cardHolderName[1]);
        $payerInfo->setCardNumber($this->card_number);
        $payerInfo->setCardCcv($this->card_ccv);
        $payerInfo->setExpired($this->card_expired_month, $this->card_expired_year);

        $paymentFactory->setPayment_method_nonce($this->braintree_nonce);
        $paymentFactory->setPayerInfo($payerInfo);
        $paymentFactory->setProductName("Hotel Quickly");
        $paymentFactory->setPrice($this->price);
        $paymentFactory->setCurrency($this->currency);

        $result = $paymentFactory->makePayment();
        return $result;
    }


}