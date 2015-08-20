<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/20/15 AD
 * Time: 11:58 PM
 */
namespace app\components\PaymentFactory;

//use app\components\PaymentFactory\PayerInfo;
use PHPUnit_Framework_TestCase;
use yii\web\Application;

class PaymentFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp(){

        require(__DIR__ . '/../../vendor/autoload.php');
        require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

        $config = require(__DIR__ . '/../../config/web.php');

        (new Application($config));



        $payerInfo = new PayerInfo();
        $payerInfo->setCardNumber("4550009107595883");
        $payerInfo->setExpired("09", "2015");
        $payerInfo->setCardCcv(111);
        $payerInfo->setCardHolderName("Chainarong", "Tangsurakit");

        $paymentFactory = new PaymentFactory();
        $paymentFactory->setCurrency("USD");
        $paymentFactory->setPrice(100);
        $paymentFactory->setProductName("Hotel Quickly");
        $paymentFactory->setPayerInfo($payerInfo);

        $result = $paymentFactory->makePayment();

        $this->assertEquals("paypal", $result->payby);
        $this->assertEquals(true, $result->status);




        $payerInfo = new PayerInfo();
        $payerInfo->setCardNumber("4111111111111111");
        $payerInfo->setExpired("09", "2015");
        $payerInfo->setCardCcv(111);
        $payerInfo->setCardHolderName("Chainarong", "Tangsurakit");

        $paymentFactory = new PaymentFactory();
        $paymentFactory->setCurrency("THB");
        $paymentFactory->setPrice(100);
        $paymentFactory->setProductName("Hotel Quickly");
        $paymentFactory->setPayerInfo($payerInfo);

        $result = $paymentFactory->makePayment();

        $this->assertEquals("braintree", $result->payby);
        $this->assertEquals(true, $result->status);


    }

    public function testException(){


        $payerInfo = new PayerInfo();
        $payerInfo->setCardNumber("123456789012345");
        $payerInfo->setExpired("09", "2015");
        $payerInfo->setCardCcv(111);
        $payerInfo->setCardHolderName("Chainarong", "Tangsurakit");

        $paymentFactory = new PaymentFactory();
        $paymentFactory->setCurrency("THB");
        $paymentFactory->setPrice(100);
        $paymentFactory->setProductName("Hotel Quickly");
        $paymentFactory->setPayerInfo($payerInfo);

        $this->setExpectedException("Exception", "AMEX CARD ONLY SUPPORT USD.");
    }

}
