<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $SurrogateKey
 * @property string $AgeGroup
 */
class AgeGroup extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.DimAgeGroup';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SurrogateKey';

    /**
     * @var array
     */
    protected $fillable = ['AgeGroup'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
