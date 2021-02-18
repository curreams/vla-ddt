<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SavedSearch extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saved_searches';

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
        'title',
        'description',
        'user_id',
        'created_by',
        'updated_by'
    ];

    public function getCreatedAtAttribute($value)
    {
        $date =new Carbon($value);
        return $date->format('d/m/Y');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function filters()
    {
        return $this->belongsToMany('App\Models\Filter', 'saved_searches_has_filters', 'saved_searches_id', 'filter_id');
    }

}
