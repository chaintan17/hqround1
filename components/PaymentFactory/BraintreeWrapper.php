<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/19/15 AD
 * Time: 11:31 PM
 */

namespace app\components\PaymentFactory;


use Braintree_Configuration;

class BraintreeWrapper implements iPaymentInterface
{
    private $_price;
    private $_nonce;

    /**
     * BriantreeWrapper constructor.
     */
    public function __construct()
    {
        Braintree_Configuration::environment('sandbox');
        Braintree_Configuration::merchantId('vhwd2qnttnm3s8xk');
        Braintree_Configuration::publicKey('cs4z9qvgr5xm593t');
        Braintree_Configuration::privateKey('fa7a20f90d2764ae20cba0f3abc39dbc');
    }

    /**
     * @return PaymentResult
     */
    public function makePayment()
    {
        $braintreeResult = \Braintree_Transaction::sale([
            'amount' => $this->_price,
            'paymentMethodNonce' => $this->_nonce
        ]);
        $result = new PaymentResult();
        $result->status = $braintreeResult->success;
        if($result->status){
            $result->message = $braintreeResult->transaction->id;
        }else{
            $result->message = $braintreeResult->message;
        }
        return $result;
    }

    /**
     * @param string $productname
     * @return void
     */
    public function setProductName($productname)
    {
        // TODO: Implement setProductName() method.
    }

    /**
     * @param double $price
     * @return void
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function setPayerInfo(PayerInfo $payerInfo)
    {
        // TODO: Implement setPayerInfo() method.
    }

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency($currency)
    {
        // TODO: Implement setCurrency() method.
    }

    public function setNonce($_braintreenonce)
    {
        $this->_nonce = $_braintreenonce;
    }
}