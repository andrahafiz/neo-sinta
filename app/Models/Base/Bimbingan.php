<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Lecture;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bimbingan
 *
 * @property int $id
 * @property string $pembahasan
 * @property string|null $catatan
 * @property Carbon $tanggal_bimbingan
 * @property Carbon|null $approved_at
 * @property int $mahasiswas_id
 * @property string $type_pembimbing
 * @property int $dosen_pembimbing
 * @property int|null $status
 * @property string $bimbingan_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Lecture $lecture
 * @property Mahasiswa $mahasiswa
 *
 * @package App\Models\Base
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

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'dosen_pembimbing');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
    }

    public function scopeDataMahasiswa(Builder $query)
    {
        $mahasiswaId = auth()->user()->id;
        return $query->where('mahasiswas_id', $mahasiswaId);
    }

    public function scopeDataDosen(Builder $query)
    {
        $dosenId = auth()->user()->id;
        return $query->where('dosen_pembimbing', $dosenId);
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
