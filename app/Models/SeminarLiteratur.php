<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SeminarLiteratur
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SeminarLiteratur extends Model
{
	protected $table = 'seminar_literatur';

	protected $casts = [
		'status' => 'int',
		'date' => 'datetime',
		'pic' => 'int',
		'check_in_ppt' => 'string',
		'check_in_literatur' => 'string',
		'mahasiswas_id' => 'int',
		'approval_by' => 'int'
	];

	protected $fillable = [
		'status',
		'date',
		'pic',
		'check_in_ppt',
		'check_in_literatur',
		'mahasiswas_id',
		'approval_by'
	];

	public function lecture()
	{
		return $this->belongsTo(Lecture::class, 'approval_by');
	}

	public function mahasiswa()
	{
		return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
	}
}
