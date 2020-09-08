<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filters';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'table',
        'surrogate_key',
        'parent_id',
        'description',
        'type',
        'value',
        'created_by',
        'updated_by'
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Filter', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Filter', 'parent_id');
    }

    /*
    *Get Filter Type
    */
    public function filterType()
    {
        return $this->belongsTo('App\Models\FilterType', 'type');
    }
}
