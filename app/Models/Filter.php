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
    protected $appends = ['selected', 'color'];



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
    public function savedSearches()
    {
        return $this->belongsToMany('App\Models\SavedSearch', 'saved_searches_has_filters', 'filter_id','saved_searches_id');
    }
    public function getSelectedAttribute()
    {
        return false;
    }
    public function getColorAttribute()
    {
        $filter_type = FilterType::find($this->filter_type, 'color');
        return $filter_type["color"];
    }

    public function getValueAttribute($value)
    {
        if(strcmp($value, "LGA")==0 || self::startsWith($value, "SA") ){
            return $value;
        }
        return ucwords(strtolower($value));
    }


    private function startsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
    }
}
