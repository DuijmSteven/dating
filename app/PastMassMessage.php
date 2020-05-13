<?php

namespace App;

class PastMassMessage extends TimeZonedModel
{
    public $table = 'past_mass_messages';

    protected $fillable = [
        'body',
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
}
