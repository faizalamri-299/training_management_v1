<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class discounts extends Model
{
    
    public $primaryKey = 'discntPK';
    public $timestamps = false;
    //
    protected $fillable = ['trainFK', 'discntVal', 'discntFrom','discntTo', 'discntCode', 'discntAuto'];
}
