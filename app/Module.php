<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 * @package App
 */
class Module extends Model
{
    public $table = 'modules';

    public $fillable = [
        'name',
        'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function layoutParts()
    {
        return $this->belongsToMany('App\LayoutPart')->withPivot('priority');
    }

    /**
     * @param string $layoutPartName
     */
    public function isActiveOnLayoutPart(string $layoutPartName)
    {
        $layoutParts = LayoutPart::where('name', $layoutPartName)
            ->whereHas('modules', function ($query) use ($layoutPartName) {
                $query->where('name', $this->name);
            })
            ->get()->toArray();

        return !empty($layoutParts);
    }
}
