<?php

namespace App;

class PastMassMessage extends TimeZonedModel
{
    public $table = 'past_mass_messages';

    protected $fillable = [
        'body',
        'userCount'
    ];

    public function getId()
    {
        return $this->id;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getUserCount()
    {
        return $this->user_count;
    }

    public function setUserCount(int $userCount)
    {
        $this->user_count = $userCount;
    }
}
