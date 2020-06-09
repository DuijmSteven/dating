<?php

namespace App;

/**
 * Class Article
 * @package App
 */
class Article extends TimeZonedModel
{
    public $fillable = [
        'title',
        'body',
        'status',
        'meta_description',
        'image_filename',
        'slug'
    ];

    public static $statuses = [
        0 => 'private',
        1 => 'public'
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
    public function getImageFilename()
    {
        return $this->image_filename;
    }

    /**
     * @param string $body
     */
    public function setImageFilename(string $imageFilename)
    {
        $this->image_filename = $imageFilename;
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription(string $metaDescription)
    {
        $this->meta_description = $metaDescription;
    }
}
