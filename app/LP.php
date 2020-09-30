<?php

namespace App;

/**
 * Class LP
 * @package App
 */
class LP extends TimeZonedModel
{
    const FIRST_FULL_LP = 1;
    const ADS_LP_1 = 2;
    const ADS_LP_2 = 3;

    public $fillable = [
        'title',
        'description',
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
