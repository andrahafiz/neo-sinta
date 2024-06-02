<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SeminarProyek
 * 
 * @property int $id
 * @property string $title
 * @property int|null $status
 * @property Carbon $date
 * @property int $pic
 * @property string $dok_per_sem_proyek
 * @property int $mahasiswas_id
 * @property Carbon|null $proposed_at
 * @property Carbon|null $in_review_at
 * @property Carbon|null $approved_at
 * @property Carbon|null $declined_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Mahasiswa $mahasiswa
 * @property Lecture $lecture
 *
 * @package App\Models
 */
class SeminarProyek extends Model
{
	use SoftDeletes;
	protected $table = 'seminar_proyek';

	protected $casts = [
		'status' => 'int',
		'date' => 'datetime',
		'pic' => 'int',
		'mahasiswas_id' => 'int',
		'proposed_at' => 'datetime',
		'in_review_at' => 'datetime',
		'approved_at' => 'datetime',
		'declined_at' => 'datetime'
	];

	protected $fillable = [
		'title',
		'status',
		'date',
		'pic',
		'dok_per_sem_proyek',
		'mahasiswas_id',
		'proposed_at',
		'in_review_at',
		'approved_at',
		'declined_at'
	];

	public function mahasiswa()
	{
		return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
	}

	public function lecture()
	{
		return $this->belongsTo(Lecture::class, 'pic');
	}
}
