<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginModel extends Model
{

    public function partnerExists($email)
    {
        return  DB::table('users')
            ->select(['users.name', 'users.email', 'users.company','users.level','users.cost_center','users.active'])
            ->join('lowcost_partner','lowcost_partner.company','=','users.company')
            ->where('users.email', '=',$email)->where('lowcost_partner.active','=','1')
            ->exists();
    }
}
