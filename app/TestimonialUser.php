<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TestimonialUser
 * @package App
 */
class TestimonialUser extends Model
{
    public $fillable = [
        'name',
        'username',
        'dob',
        'gender',
        'status',
        'coupled_with_id'
    ];

    public $dates = [
        'dob'
    ];

    public static $statuses = [
        0 => 'private',
        1 => 'public'
    ];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUserName(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->body;
    }

    /**
     * @param string $dob
     */
    public function setDob(string $dob)
    {
        $this->dob = $dob;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCoupledWithId()
    {
        return $this->pretend_at;
    }

    /**
     * @param string $coupledWithId
     */
    public function setCoupledWithId(string $coupledWithId)
    {
        $this->coupled_with_id = $coupledWithId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testimonial()
    {
        return $this->belongsTo('App\Testimonial');
    }
}
