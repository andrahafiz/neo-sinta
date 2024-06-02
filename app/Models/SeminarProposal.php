<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SeminarProposal
 * 
 * @property int $id
 * @property Carbon $date
 * @property int|null $status
 * @property string $pembahasan
 * @property string|null $catatan
 * @property string $title
 * @property int $pic
 * @property string|null $saran
 * @property string $draf_pro
 * @property string $pro_ppt
 * @property string $dok_persetujuan_pro
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
class SeminarProposal extends Model
{
	use SoftDeletes;
	protected $table = 'seminar_proposal';

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
		'draf_pro',
		'pro_ppt',
		'dok_persetujuan_pro',
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
