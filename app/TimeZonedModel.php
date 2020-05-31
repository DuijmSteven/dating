<?php


namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TimeZonedModel extends Model
{
    const TIMEZONE = 'Europe/Amsterdam';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @param string $createdAt
     * @return Carbon|string
     * @throws \Exception
     */
    public function getCreatedAtAttribute(string $createdAt)
    {
        return (new Carbon($createdAt))->tz(self::TIMEZONE);
    }

    /**
     * @param string $updatedAt
     * @return Carbon|string
     * @throws \Exception
     */
    public function getUpdatedAtAttribute(string $updatedAt)
    {
        return (new Carbon($updatedAt))->tz(self::TIMEZONE);
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() {
        return $this->created_at->tz(self::TIMEZONE);
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt() {
        return $this->updated_at->tz(self::TIMEZONE);
    }
}