<?php
/**
 * Created by PhpStorm.
 * User: por
 * Date: 8/19/15 AD
 * Time: 10:58 PM
 */

namespace app\models;


class PayerInfo
{
    private $_buyerName;
    private $_cardHolderName;
    private $_cardNumber;
    private $_cardCcv;
    private $_cardExpired;

    public function getBuyerName()
    {
        return $this->_buyerName;
    }

    public function setBuyerName($firstname, $lastname)
    {
        $this->_buyerName = array($firstname, $lastname);
    }

    public function getCardHolderName()
    {
        return $this->_cardHolderName;
    }

    public function setCardHolderName($firstname, $lastname)
    {
        $this->_cardHolderName = array($firstname, $lastname);
    }

    public function setCardNumber($cardNumber)
    {
        $this->_cardNumber = $cardNumber;
    }

    public function setCardCcv($cardCcv)
    {
        $this->_cardCcv = $cardCcv;
    }

    public function setExpired($month, $year)
    {
        $this->_cardExpired = array($month, $year);
    }

    public function getCardNumber()
    {
        return $this->_cardNumber;
    }

    public function getCardCcv()
    {
        return $this->_cardCcv;
    }

    public function getCardExpired()
    {
        return $this->_cardExpired;
    }


}