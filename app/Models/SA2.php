<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $SurrogateKey
 * @property string $SA2
 */
class SA2 extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.DimSA2';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SurrogateKey';

    /**
     * @var array
     */
    protected $fillable = ['SA2'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
