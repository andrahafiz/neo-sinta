<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Models\Sitin;
use App\Models\Bimbingan;
use App\Models\SeminarHasil;
use App\Models\SeminarProyek;
use App\Models\SeminarProposal;
use App\Models\SidangMejaHijau;
use App\Models\TitleSubmission;
use App\Models\SeminarLiteratur;
use App\Models\SeminarPraProposal;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Lecture
 *
 * @property int $id
 * @property string $name
 * @property string|null $nip
 * @property string $email
 * @property string $password
 * @property bool $is_active
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|Bimbingan[] $bimbingans
 * @property Collection|SeminarHasil[] $seminar_hasils
 * @property Collection|SeminarLiteratur[] $seminar_literaturs
 * @property Collection|SeminarPraProposal[] $seminar_pra_proposals
 * @property Collection|SeminarProposal[] $seminar_proposals
 * @property Collection|SeminarProyek[] $seminar_proyeks
 * @property Collection|SidangMejaHijau[] $sidang_meja_hijaus
 * @property Collection|Sitin[] $sitins
 * @property Collection|TitleSubmission[] $title_submissions
 *
 * @package App\Models
 */
class Lecture extends Model
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;
    protected $table = 'lecture';

    protected $casts = [
        'is_active' => 'bool'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'nip',
        'email',
        'password',
        'is_active',
        'remember_token'
    ];

    public function mahasiswa_bimbingan()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_pembimbing');
    }

    public function seminar_hasils()
    {
        return $this->hasMany(SeminarHasil::class, 'approval_by');
    }

    public function seminar_literaturs()
    {
        return $this->hasMany(SeminarLiteratur::class, 'pic');
    }

    public function seminar_pra_proposals()
    {
        return $this->hasMany(SeminarPraProposal::class, 'approval_by');
    }

    public function seminar_proposals()
    {
        return $this->hasMany(SeminarProposal::class, 'pic');
    }

    public function seminar_proyeks()
    {
        return $this->hasMany(SeminarProyek::class, 'pic');
    }

    public function sidang_meja_hijaus()
    {
        return $this->hasMany(SidangMejaHijau::class, 'pic');
    }

    public function sitins()
    {
        return $this->hasMany(Sitin::class, 'approval_by');
    }

    public function title_submissions()
    {
        return $this->hasMany(TitleSubmission::class, 'pic');
    }
}
