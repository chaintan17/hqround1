<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/19/15 AD
 * Time: 10:43 PM
 */

namespace app\components\PaymentFactory;


use yii\base\Exception;

class PaymentFactory
{
    /**
     * @var PayerInfo $_payerInfo
     */
    private $_payerInfo;
    private $_productName;
    private $_price;
    private $_currency;
    private $_braintreenonce;

    /**
     * @return PaymentResult|null
     */
    public function makePayment(){
        $result = null;
        $whichPayment = $this->whichPayment();
        if($whichPayment == "paypal"){
            $paypalWrapper = new PaypalWrapper();
            $paypalWrapper->setPayerInfo($this->_payerInfo);
            $paypalWrapper->setPrice($this->_price);
            $paypalWrapper->setProductName($this->_productName);
            $paypalWrapper->setCurrency($this->_currency);
            $result = $paypalWrapper->makePayment();
        }else{
            $btlWrapper = new BraintreeWrapper();
            $btlWrapper->setPayerInfo($this->_payerInfo);
            $btlWrapper->setPrice($this->_price);
            $btlWrapper->setProductName($this->_productName);
            $btlWrapper->setCurrency($this->_currency);
            $result = $btlWrapper->makePayment();
        }
        return $result;
    }

    public function setPayerInfo(PayerInfo $payerInfo){
        $this->_payerInfo = $payerInfo;
    }

    public function setProductName($string)
    {
        $this->_productName = $string;
    }

    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }

    public function setPayment_method_nonce($val){
        $this->_braintreenonce = $val;
    }

    private function whichPayment()
    {
        if(strlen($this->_payerInfo->getCardNumber() == 15)){
            if($this->_currency != "USD"){
                throw new Exception("AMEX CARD ONLY SUPPORT USD.");
            }
            return "paypal";
        }
        if(in_array($this->_currency, ['USD', 'EUR', 'AUD'])){
            return "paypal";
        }else{
            return "braintree";
        }

    }
}
