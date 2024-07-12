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

    public const STATUS_PROPOSED = 'proposed';
    public const STATUS_APPROVE = 'approve';
    public const STATUS_DECLINE = 'declined';

    protected $casts = [
        'status' => 'string',
        'date' => 'datetime',
        'pic' => 'int',
        'mahasiswas_id' => 'int',
        'proposed_at' => 'datetime',
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
        'note',
        'nilai_seminar_proyek',
        'tanggal_seminar_proyek',
        'proposed_at',
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
