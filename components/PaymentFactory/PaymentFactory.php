<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/19/15 AD
 * Time: 10:43 PM
 */

namespace app\components\PaymentFactory;


class PaymentFactory
{
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
        if($this->_braintreenonce == null || $this->_braintreenonce == ""){
            $paypalWrapper = new PaypalWrapper();
            $paypalWrapper->setPayerInfo($this->_payerInfo);
            $paypalWrapper->setPrice($this->_price);
            $paypalWrapper->setProductName($this->_productName);
            $paypalWrapper->setCurrency($this->_currency);
            $result = $paypalWrapper->makePayment();
        }else{
            $paypalWrapper = new BraintreeWrapper();
            $paypalWrapper->setNonce($this->_braintreenonce);
            $paypalWrapper->setPayerInfo($this->_payerInfo);
            $paypalWrapper->setPrice($this->_price);
            $paypalWrapper->setProductName($this->_productName);
            $paypalWrapper->setCurrency($this->_currency);
            $result = $paypalWrapper->makePayment();
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
}
