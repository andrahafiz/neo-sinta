<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SeminarPraProposal
 *
 * @property int $id
 * @property Carbon $date
 * @property string $title
 * @property int|null $status
 * @property int $pic
 * @property Carbon|null $proposed_at
 * @property Carbon|null $approved_at
 * @property Carbon|null $declined_at
 * @property string $draf_pra_pro
 * @property string $pra_pro_ppt
 * @property string $dok_persetujuan_pra_pro
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
class SeminarPraProposal extends Model
{
    use SoftDeletes;
    protected $table = 'seminar_pra_proposal';

    public const STATUS_PROPOSED = 'proposed';
    public const STATUS_APPROVE = 'approve';
    public const STATUS_DECLINE = 'declined';
    protected $casts = [
        'date' => 'datetime',
        'status' => 'string',
        'pic' => 'int',
        'proposed_at' => 'datetime',
        'approved_at' => 'datetime',
        'declined_at' => 'datetime',
        'mahasiswas_id' => 'int',
        'approval_by' => 'int'
    ];

    protected $fillable = [
        'date',
        'title',
        'status',
        'pic',
        'proposed_at',
        'approved_at',
        'declined_at',
        'draf_pra_pro',
        'pra_pro_ppt',
        'dok_persetujuan_pra_pro',
        'mahasiswas_id',
        'note',
        'approval_by'
    ];

    public function scopeDataMahasiswa()
    {
        $auth = auth()->user()->id;
        return $this->where('mahasiswas_id', $auth);
    }

    public function scopeDataDosen()
    {
        $auth = auth()->user()->id;
        return $this->where('pic', $auth);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'pic');
    }

    public function approvalBy()
    {
        return $this->belongsTo(Lecture::class, 'approval_by');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
    }

    public function getStatusTextAttribute()
    {
        $status = $this->status;
        switch ($this->status) {
            case 'proposed':
                $status = 'Proposed';
                break;
            case 'approve':
                $status = 'Approve';
                break;
            case 'declined':
                $status = 'Tolak';
                break;
            default:
                $status;
                break;
        }
        return $status;
    }
}
