<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $SA2_MAINCODE_2016
 * @property string $SA2_5DIGITCODE_2016
 * @property string $SA2_NAME_2016
 * @property string $SA3_CODE_2016
 * @property string $SA3_NAME_2016
 * @property string $SA4_CODE_2016
 * @property string $SA4_NAME_2016
 * @property string $GCCSA_CODE_2016
 * @property string $GCCSA_NAME_2016
 * @property string $STATE_CODE_2016
 * @property string $STATE_NAME_2016
 * @property float $AREA_ALBERS_SQKM
 */
class SARelation extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ddt.SA2_2016_AUST';

    /**
     * @var array
     */
    protected $fillable = ['SA2_MAINCODE_2016', 'SA2_5DIGITCODE_2016', 'SA2_NAME_2016', 'SA3_CODE_2016', 'SA3_NAME_2016', 'SA4_CODE_2016', 'SA4_NAME_2016', 'GCCSA_CODE_2016', 'GCCSA_NAME_2016', 'STATE_CODE_2016', 'STATE_NAME_2016', 'AREA_ALBERS_SQKM'];

    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'sqlsrv';

}
