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

    public const STATUS_PROPOSED = 'proposed';
    public const STATUS_APPROVE = 'approve';
    public const STATUS_DECLINE = 'declined';

    protected $casts = [
        'status' => 'string',
        'date' => 'datetime',
        'tanggal_seminar_literatur' => 'datetime',
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
        'note',
        'nilai_seminar_literatur',
        'tanggal_seminar_literatur',
        'check_in_literatur',
        'mahasiswas_id',
        'approval_by'
    ];

    public function getTanggalSeminarLiteraturAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['tanggal_seminar_literatur'])
            ->isoFormat('dddd, D MMMM Y HH:mm');
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
