<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Sitin
 * 
 * @property int $id
 * @property Carbon $date
 * @property Carbon|null $check_in
 * @property Carbon|null $check_out
 * @property int|null $duration
 * @property string|null $check_in_proof
 * @property string|null $check_out_proof
 * @property string|null $check_out_document
 * @property int|null $status
 * @property int $mahasiswas_id
 * @property int $approval_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Lecture $lecture
 * @property Mahasiswa $mahasiswa
 *
 * @package App\Models
 */
class Sitin extends Model
{
	use SoftDeletes;
	protected $table = 'sitin';

	protected $casts = [
		'date' => 'datetime',
		'check_in' => 'datetime',
		'check_out' => 'datetime',
		'duration' => 'int',
		'status' => 'int',
		'mahasiswas_id' => 'int',
		'approval_by' => 'int'
	];

	protected $fillable = [
		'date',
		'check_in',
		'check_out',
		'duration',
		'check_in_proof',
		'check_out_proof',
		'check_out_document',
		'status',
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
