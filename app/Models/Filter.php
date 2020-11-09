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
    protected $appends = ['selected'];

    public function getSelectedAttribute()
    {
        return false;
    }

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
        'searchable',
        'filter_type',
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
        return $this->belongsTo('App\Models\FilterType', 'filter_type');
    }

    public function getValueAttribute($value)
    {
        return ucwords(strtolower($value));
    }
}
