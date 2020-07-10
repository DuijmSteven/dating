<?php

namespace App;

use Carbon\Carbon;

class Expense extends TimeZonedModel
{
    const PAYEE_XPARTNERS = 1;
    const PAYEE_GOOGLE = 2;
    const PAYEE_OTHER = 3;

    const TYPE_ADS = 1;
    const TYPE_INFRASTRUCTURE = 2;
    const TYPE_SALARY = 3;
    const TYPE_OTHER = 4;

    public $table = 'expenses';

    protected $fillable = [
        'description',
        'amount',
        'payee',
        'type',
        'takes_place_at'
    ];

    protected $dates = [
        'takes_place_at'
    ];

    public static function payeeDescriptionPerId()
    {
        return [
            self::PAYEE_XPARTNERS => 'X-Partners',
            self::PAYEE_GOOGLE => 'Google',
            self::PAYEE_OTHER => 'Other',
        ];
    }

    public static function typeDescriptionPerId()
    {
        return [
            self::TYPE_ADS => 'Ads',
            self::TYPE_INFRASTRUCTURE => 'Infrastructure',
            self::TYPE_SALARY => 'Salary',
            self::TYPE_OTHER => 'Other',
        ];
    }

    public function getId()
    {
        return $this->id;
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

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    public function getAmountInEuroAttribute()
    {
        return $this->amount/100;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(int $type)
    {
        $this->type = $type;
    }

    public function getPayee()
    {
        return $this->payee;
    }

    public function setPayee(int $payee)
    {
        $this->payee = $payee;
    }

    /**
     * @return Carbon
     */
    public function getTakesPlaceAt() {
        return $this->takes_place_at->tz(self::TIMEZONE);
    }

    /**
     * @param Carbon $takesPlaceAt
     */
    public function setTakesPlaceAt(Carbon $takesPlaceAt)
    {
        $this->takes_place_at = $takesPlaceAt->setTimezone('UTC');
    }
}
