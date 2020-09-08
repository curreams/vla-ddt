<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $SurrogateKey
 * @property string $Centre
 */
class Centre extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.DimCentre';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SurrogateKey';

    /**
     * @var array
     */
    protected $fillable = ['Centre'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
