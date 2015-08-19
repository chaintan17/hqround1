<?php

/* @var $this yii\web\View */
/* @var $model \app\models\PaymentForm */
/* @var $paymentResult \app\models\PaymentResult  */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'HQ Round1 PHP';
?>

<?php if($paymentResult->status):?>
    <div class="alert alert-success">
        Thank you for your payment! <?= $paymentResult->message?>
    </div>
<?php elseif($paymentResult->status === false):?>
<div class="alert alert-danger">
    <?= $paymentResult->message?>
</div>
<?php endif?>

<div class="row">
    <?php $form = ActiveForm::begin([
        'id' => 'payment-form',
        'enableClientValidation' => true,
    ]);
    /* @var $form ActiveForm */
    ?>
    <div class="col-sm-6">
    <h2>Order</h2>
        <?= $form->field($model, "braintree_nonce")->hiddenInput()?>
    <?= $form->field($model, 'price')?>
    <?= $form->field($model, 'currency')->dropDownList(\app\models\PaymentForm::$currenyList)?>
    <?= $form->field($model, 'full_name')?>
    </div>
    <div class="col-sm-6">
    <h2>Payment</h2>
    <?= $form->field($model, 'card_holder')->textInput(['data-braintree-name' => 'cardholder_name'])?>
    <?= $form->field($model, 'card_number')->textInput(['data-braintree-name' => 'number'])?>

        <label>Card Expiration</label>
        <?php $fieldEm = $form->field($model, 'card_expired_month')->textInput();
        $fieldEm->template = "{error}";
        echo $fieldEm;
        ?>

        <?php $fieldEm = $form->field($model, 'card_expired_year')->textInput();
        $fieldEm->template = "{error}";
        echo $fieldEm;
        ?>
    <?= Html::activeTextInput($model, 'card_expired_month', ['placeholder' => 'MM', 'data-braintree-name' => 'expiration_month']);?> /
        <?= Html::activeTextInput($model, 'card_expired_year', ['placeholder' => 'YYYY', 'data-braintree-name' => 'expiration_year']);?>


    <?= $form->field($model, 'card_ccv')->textInput(['data-braintree-name' => 'cvv'])?>
    </div>
    <div class="col-sm-12 text-center">
        <?= Html::submitButton("Submit", ['class' => 'btn btn-primary', 'value' => 'Pay'])?>
    </div>
    <?php ActiveForm::end()?>
</div>


<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
    var clientToken = new braintree.api.Client({clientToken: "<?=$clientToken?>"});
    $(document).ready(function(){
        $('#payment-form').on('afterValidate', function(e){
//            e.preventDefault();
//            $('#payment-form').submit();
            e.preventDefault();
            clientToken.tokenizeCard({
                number: $("#paymentform-card_number").text(),
                expirationDate: $("#paymentform-card_expired_month").text() + "/" + $("paymentform-card_expired_year").text()
            }, function (err, nonce) {
                // Send nonce to your server
                console.log(err);
                console.log(nonce);
                $('#paymentform-braintree_nonce').val(nonce);
                $('#payment-form').submit();
            });
        });

    })
</script>