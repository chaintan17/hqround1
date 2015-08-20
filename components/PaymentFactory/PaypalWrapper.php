<?php

namespace app\components\PaymentFactory;

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;


class PaypalWrapper implements iPaymentInterface
{
    private $_clientId;
    private $_clientSecret;
    private $_productName;
    private $_price;
    private $_currency;
    /**
     * @var PayerInfo
     */
    private $_payerInfo;

    public function __construct(){

            $this->_clientId = \Yii::$app->params["paypalClientId"];
            $this->_clientSecret = \Yii::$app->params["paypalClientSecret"];
    }

    function getApiContext()
    {
        $clientId = $this->_clientId;
        $clientSecret = $this->_clientSecret;

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        return $apiContext;
    }

    public function makePayment()
    {
        $result = new PaymentResult();

        $card = new CreditCard();
        $card->setType("visa")
            ->setNumber($this->_payerInfo->getCardNumber())
            ->setExpireMonth((string) $this->_payerInfo->getCardExpired()[0])
            ->setExpireYear((string) $this->_payerInfo->getCardExpired()[1])
            ->setCvv2((string) $this->_payerInfo->getCardCcv())
            ->setFirstName($this->_payerInfo->getCardHolderName()[0])
            ->setLastName($this->_payerInfo->getCardHolderName()[1]);

        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        $item1 = new Item();
        $item1->setName($this->_productName)
            ->setDescription($this->_productName)
            ->setCurrency($this->_currency)
            ->setQuantity(1)
            ->setPrice($this->_price);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $amount = new Amount();
        $amount->setCurrency($this->_currency)
            ->setTotal($this->_price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        try {
            $paypalw = new PaypalWrapper();
            $payment->create($paypalw->getApiContext());
            $paymentId = $payment->getId();
            $result->status = true;
            $result->message = $paymentId;
        } catch (\Exception $ex) {
            $result->status = false;
            $result->message = $ex->getMessage();
        }

        return $result;
    }

    public function setProductName($productname)
    {
        $this->_productName = $productname;
    }


    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function setPayerInfo(PayerInfo $payerInfo)
    {
        $this->_payerInfo = $payerInfo;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }
}