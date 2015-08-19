<?php

namespace app\controllers;

use app\models\BraintreeWrapper;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\PaymentForm;
use app\models\PaymentResult;
use Braintree_ClientToken;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $paymentForm = new PaymentForm();
        $paymentResult = new PaymentResult();

        $braintreeWrapper = new BraintreeWrapper();
        $clientToken = Braintree_ClientToken::generate();

        if ($paymentForm->load(Yii::$app->request->post())) {
            $paymentResult = $paymentForm->makePayment();
        }
        $viewModel = [
            'model' => $paymentForm,
            'paymentResult' => $paymentResult,
            'clientToken' => $clientToken,
        ];
        return $this->render('index', $viewModel);
    }

    public function actionGetBraintreeToken(){
        $braintreeWrapper = new BraintreeWrapper();
        echo($clientToken = Braintree_ClientToken::generate());
    }

}
