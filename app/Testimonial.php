<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Testimonial
 * @package App
 */
class Testimonial extends Model
{
    public $fillable = [
        'title',
        'body',
        'status',
        'pretend_at'
    ];

    public static $statuses = [
        0 => 'private',
        1 => 'public'
    ];

    public $dates = [
        'pretend_at'
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
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
    public function getPretendAt()
    {
        return $this->pretend_at;
    }

    /**
     * @param string $pretendAt
     */
    public function setPretendAt(string $pretendAt)
    {
        $this->pretend_at = $pretendAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\TestimonialUser');
    }
}
