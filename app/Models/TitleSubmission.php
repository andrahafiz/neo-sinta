<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TitleSubmission
 * 
 * @property int $id
 * @property string $title
 * @property int|null $status
 * @property Carbon $date
 * @property int $pic
 * @property int $mahasiswas_id
 * @property int $pembimbing_1
 * @property int $pembimbing_2
 * @property string $konsentrasi_ilmu
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Mahasiswa $mahasiswa
 * @property Lecture $lecture
 *
 * @package App\Models
 */
class TitleSubmission extends Model
{
	use SoftDeletes;
	protected $table = 'title_submission';

	protected $casts = [
		'status' => 'int',
		'date' => 'datetime',
		'pic' => 'int',
		'mahasiswas_id' => 'int',
		'pembimbing_1' => 'int',
		'pembimbing_2' => 'int'
	];

	protected $fillable = [
		'title',
		'status',
		'date',
		'pic',
		'mahasiswas_id',
		'pembimbing_1',
		'pembimbing_2',
		'dok_pengajuan_judul',
		'konsentrasi_ilmu'
	];

	public function mahasiswa()
	{
		return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
	}

	public function lecture()
	{
		return $this->belongsTo(Lecture::class, 'pic');
	}

	public function pembimbing1()
	{
		return $this->belongsTo(Lecture::class, 'pembimbing_1');
	}

	public function pembimbing2()
	{
		return $this->belongsTo(Lecture::class, 'pembimbing_2');
	}
}
