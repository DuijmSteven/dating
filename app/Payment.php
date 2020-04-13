<?php

namespace App;

class Payment extends TimeZonedModel
{
    const STATUS_STARTED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_ERROR = 5;

    public $table = 'payments';

    protected $fillable = [
        'method',
        'creditpack_id',
        'description',
        'amount',
        'status',
        'transaction_id'
    ];

    public function peasant()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function creditpack()
    {
        return $this->belongsTo(Creditpack::class, 'creditpack_id', 'id');
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTracked(): bool
    {
        return $this->tracked;
    }

    /**
     * @param bool $tracked
     */
    public function setTracked(bool $tracked)
    {
        $this->tracked = $tracked;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }


    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    public function setTransactionId(int $transactionId)
    {
        $this->transaction_id = $transactionId;
    }

    public function getCreditpackId()
    {
        return $this->creditpack_id;
    }

    public function setCreditpackId(int $creditpackId)
    {
        $this->creditpack_id = $creditpackId;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
    }
}
