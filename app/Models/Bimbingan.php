<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bimbingan
 * 
 * @property int $id
 * @property string $pembahasan
 * @property int|null $catatan
 * @property Carbon $tanggal_bimbingan
 * @property Carbon|null $approved_at
 * @property int $mahasiswas_id
 * @property int $dosen_pembimbing
 * @property int|null $status
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Lecture $lecture
 * @property Mahasiswa $mahasiswa
 *
 * @package App\Models
 */
class Bimbingan extends Model
{
	use SoftDeletes;
	protected $table = 'bimbingan';

	protected $casts = [
		'catatan' => 'int',
		'tanggal_bimbingan' => 'datetime',
		'approved_at' => 'datetime',
		'mahasiswas_id' => 'int',
		'dosen_pembimbing' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'pembahasan',
		'catatan',
		'tanggal_bimbingan',
		'approved_at',
		'mahasiswas_id',
		'dosen_pembimbing',
		'status',
		'type'
	];

	public function lecture()
	{
		return $this->belongsTo(Lecture::class, 'dosen_pembimbing');
	}

	public function mahasiswa()
	{
		return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
	}
}
