<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Base\Bimbingan as BimbinganBase;

class Bimbingan extends BimbinganBase
{

    protected $casts = [
        'tanggal_bimbingan' => 'datetime',
        'approved_at' => 'datetime',
        'mahasiswa_id' => 'int',
        'dosen_pembimbing' => 'int',
        'status' => 'int',
        // 'bimbingaable_id' => 'int'
    ];

    protected $fillable = [
        'pembahasan',
        'catatan',
        'tanggal_bimbingan',
        'approved_at',
        'mahasiswas_id',
        'dosen_pembimbing',
        'type_pembimbing',
        'status',
        'bimbingan_type'
    ];
}
