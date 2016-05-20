<?php

namespace easyPaypal;

class Notification
{
    private $id;
    private $txnId;
    private $txnType;
    private $receiverEmail;
    private $paymentStatus;
    private $pendingReason;
    private $reasonCode;
    private $custom;
    private $invoice;

    /**
     * Notification constructor.
     * @param $id
     * @param $txnId
     * @param $txnType
     * @param $receiverEmail
     * @param $paymentStatus
     * @param $pendingReason
     * @param $reasonCode
     * @param $custom
     * @param $invoice
     */
    public function __construct($id, $txnId, $txnType, $receiverEmail, $paymentStatus, $pendingReason, $reasonCode, $custom, $invoice)
    {
        $this->id = $id;
        $this->txnId = $txnId;
        $this->txnType = $txnType;
        $this->receiverEmail = $receiverEmail;
        $this->paymentStatus = $paymentStatus;
        $this->pendingReason = $pendingReason;
        $this->reasonCode = $reasonCode;
        $this->custom = $custom;
        $this->invoice = $invoice;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTxnId()
    {
        return $this->txnId;
    }

    /**
     * @param mixed $txnId
     */
    public function setTxnId($txnId)
    {
        $this->txnId = $txnId;
    }

    /**
     * @return mixed
     */
    public function getTxnType()
    {
        return $this->txnType;
    }

    /**
     * @param mixed $txnType
     */
    public function setTxnType($txnType)
    {
        $this->txnType = $txnType;
    }

    /**
     * @return mixed
     */
    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    /**
     * @param mixed $receiverEmail
     */
    public function setReceiverEmail($receiverEmail)
    {
        $this->receiverEmail = $receiverEmail;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param mixed $paymentStatus
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * @return mixed
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * @param mixed $pendingReason
     */
    public function setPendingReason($pendingReason)
    {
        $this->pendingReason = $pendingReason;
    }

    /**
     * @return mixed
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param mixed $reasonCode
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
    }

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param mixed $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }
}