<?php

namespace App\Models;

use App\Models\Base\Thesis as BaseThesis;

class Thesis extends BaseThesis
{
	protected $fillable = [
		'judul_thesis',
		'konsentrasi_ilmu',
		'deskripsi',
		'mahasiswas_id',
		'pembimbing_1',
		'pembimbing_2',
		'is_active'
	];

}
