<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LayoutPartModule
 * @package App
 */
class ModuleInstance extends Model
{
    public $timestamps = false;

    public $table = 'module_instances';

    public $fillable = [
        'view_id',
        'layout_part_id',
        'module_id',
        'priority'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function layoutPart()
    {
        return $this->belongsTo('App\LayoutPart');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function view()
    {
        return $this->belongsTo('App\View');
    }
}
