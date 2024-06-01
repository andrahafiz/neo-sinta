<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SeminarPraProposal
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SeminarPraProposal extends Model
{
	protected $table = 'seminar_pra_proposal';
	
	protected $casts = [
		'date' => 'datetime',
		'status' => 'int',
		'pembahasan' => 'string',
		'saran' => 'string',
		'catatan' => 'string',
		'draf_pra_pro' => 'string',
		'pra_pro_ppt' => 'string',
		'dok_persetujuan_pra_pro' => 'string',
		'mahasiswas_id' => 'int',
		'approval_by' => 'int'
	];

	protected $fillable = [
		'date',
		'status',
		'pembahasan',
		'saran',
		'catatan',
		'draf_pra_pro',
		'pra_pro_ppt',
		'dok_persetujuan_pra_pro',
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
