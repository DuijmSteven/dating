<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
        return $this->hasOne(Creditpack::class, 'creditpack_id', 'id');
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
