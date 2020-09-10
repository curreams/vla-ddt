<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterType extends Model
{
/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filter_types';
    protected $appends = ['display'];

    public function getDisplayAttribute()
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
        'description',
        'searchable',
        'show_type',
        'color'
    ];

    /*
    *Get Filter Type
    */
    public function filters()
    {
        return $this->hasMany('App\Models\Filter','filter_type','id');
    }

    /*
    *Get Filter Type
    */
    public function showType()
    {
        return $this->belongsTo('App\Models\ShowType', 'show_type');
    }

}
