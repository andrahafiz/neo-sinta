<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Models\Lecture;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Bimbingan
 *
 * @property int $id
 * @property string $pembahasan
 * @property int|null $catatan
 * @property Carbon $tanggal_bimbingan
 * @property Carbon|null $approved_at
 * @property int $mahasiswas_id
 * @property int $dosen_pembimbing1
 * @property int $dosen_pembimbing2
 * @property int|null $status
 * @property int $bimbingaable_id
 * @property string $bimbingaable_type
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

    public const SEMINAR_PRAPROPOSAL = 'Seminar PraProposal';
    public const SEMINAR_PROPOSAL = 'Seminar Proposal';
    public const SEMINAR_PROYEK = 'Seminar Proyek';
    public const SEMINAR_HASIL = 'Seminar Hasil';
    public const SIDANG_MEJA_HIJAU = 'Sidang Meja Hijau';

    protected $casts = [
        'tanggal_bimbingan' => 'datetime',
        'approved_at' => 'datetime',
        'mahasiswas_id' => 'int',
        'dosen_pembimbing' => 'int',
        'status' => 'int',
        // 'bimbingaable_id' => 'int'
    ];

    protected $fillable = [
        'pembahasan',
        'catatan',
        'tanggal_bimbingan',
        'approved_at',
        'mahasiswas_id',
        'dosen_pembimbing',
        'type_pembimbing',
        'status',
        'bimbingan_type'
    ];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'dosen_pembimbing');
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
        return $this->where('dosen_pembimbing', $auth);
    }


    public function scopeType($query, $type_bimbingan)
    {
        return $query->where('bimbingan_type', $type_bimbingan);
    }

    public function scopeApprove()
    {
        return $this->whereNotNull('approved_at');
    }

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('tanggal_bimbingan', 'desc');
        });
    }
}
