<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $SurrogateKey
 * @property string $FinancialDisadvantage
 */
class FinancialDisadvantage extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.DimFinancialDisadvantage';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SurrogateKey';

    /**
     * @var array
     */
    protected $fillable = ['FinancialDisadvantage'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
