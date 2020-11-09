<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $SurrogateKey
 * @property string $LOTE
 */
class DataView extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.DataView';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    //protected $primaryKey = 'SurrogateKey';

    /**
     * @var array
     */
    //protected $fillable = ['LOTE'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
