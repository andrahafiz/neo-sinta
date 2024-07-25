<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Base\Mahasiswa as BaseMahasiswa;

class Mahasiswa extends BaseMahasiswa

{

    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'is_active',
        'remember_token'
    ];
}
