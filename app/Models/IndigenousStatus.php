<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $SurrogateKey
 * @property string $Indigenous
 */
class IndigenousStatus extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.DimIndigenous';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SurrogateKey';

    /**
     * @var array
     */
    protected $fillable = ['Indigenous'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
