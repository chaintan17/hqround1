<?php

namespace app\components\PaymentFactory;

interface iPaymentInterface
{
    /**
     * @return PaymentResult
     */

    public function makePayment();

    /**
     * @param string $productname
     * @return void
     */
    public function setProductName($productname);

    /**
     * @param double $price
     * @return void
     */
    public function setPrice($price);


    public function setPayerInfo(PayerInfo $payerInfo);

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency($currency);

}