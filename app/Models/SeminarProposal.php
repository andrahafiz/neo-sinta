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
 * @property string $title
 * @property int|null $status
 * @property Carbon $date
 * @property int $pic
 * @property string $draf_pro
 * @property string $pro_ppt
 * @property string $dok_persetujuan_pro
 * @property int $mahasiswas_id
 * @property int $approval_by
 * @property Carbon|null $proposed_at
 * @property Carbon|null $in_review_at
 * @property Carbon|null $approved_at
 * @property Carbon|null $declined_at
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
		'status' => 'int',
		'date' => 'datetime',
		'pic' => 'int',
		'mahasiswas_id' => 'int',
		'approval_by' => 'int',
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
		'draf_pro',
		'pro_ppt',
		'dok_persetujuan_pro',
		'mahasiswas_id',
		'approval_by',
		'proposed_at',
		'in_review_at',
		'approved_at',
		'declined_at'
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
