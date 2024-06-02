<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SeminarHasil
 * 
 * @property int $id
 * @property Carbon $date
 * @property int|null $status
 * @property string $pembahasan
 * @property string|null $catatan
 * @property string $title
 * @property int|null $pic
 * @property string|null $saran
 * @property string $dok_persetujuan_sem_hasil
 * @property string $draf_tesis
 * @property string $tesis_ppt
 * @property string $loa
 * @property string $toefl
 * @property string $plagiarisme
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
class SeminarHasil extends Model
{
	use SoftDeletes;
	protected $table = 'seminar_hasil';

	protected $casts = [
		'date' => 'datetime',
		'status' => 'int',
		'pic' => 'int',
		'mahasiswas_id' => 'int',
		'approval_by' => 'int'
	];

	protected $fillable = [
		'date',
		'status',
		'pembahasan',
		'catatan',
		'title',
		'pic',
		'saran',
		'dok_persetujuan_sem_hasil',
		'draf_tesis',
		'tesis_ppt',
		'loa',
		'toefl',
		'plagiarisme',
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
