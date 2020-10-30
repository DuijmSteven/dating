<?php

namespace App;

use App\Helpers\StorageHelper;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    public $table = 'user_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'filename',
        'visible',
        'profile'
    ];

    protected $appends = [
        'url',
        'urlThumb'
    ];

    public function getUrlAttribute()
    {
        if (config('app.env') === 'local') {
            //return null;
        }

        return StorageHelper::userImageUrl($this->user_id, $this->getFilename());
    }

    public function getUrlThumbAttribute()
    {
        return StorageHelper::userImageUrl($this->user_id, $this->getFilename(), true);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getId()
    {
        return $this->id;
    }
}
