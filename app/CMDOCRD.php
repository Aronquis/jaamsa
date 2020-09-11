<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CMDOCRD extends Model
{
    //
    protected $fillable = [
        'CardCode', 'CardName', 'LicTradNum','U_BPP_BPTP','U_BPP_BPTD','U_BPP_BPAP','U_BPP_BPAM','U_BPP_BPNO',
        'E_Mail','Phone1','Phone2','Cellular','Password','apitoken'
    ];
}
