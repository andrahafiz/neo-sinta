<?php

namespace App\Repositories\Mahasiswa;

use App\Contracts\Logging;
use App\Models\SeminarHasil;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SeminarHasilStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarHasilUpdateRequest;

class SeminarHasilRepository
{

    /**
     * @var \App\Models\SeminarHasil
     */
    protected $seminarHasil;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarHasil  $seminarHasil
     */
    public function __construct(
        SeminarHasil $seminarHasil,
    ) {
        $this->seminarHasil = $seminarHasil;
    }

    /**
     * @param  \App\Http\Requests\SeminarHasilStoreRequest  $request
     * @return \App\Models\SeminarHasil
     */
    public function store(SeminarHasilStoreRequest $request)
    {
        $input = $request->safe([
            'draf_tesis', 'tesis_ppt', 'dok_persetujuan_sem_hasil', 'loa', 'toefl', 'plagiarisme'
        ]);

        $file_draf_tesis = $request->file('draf_tesis');
        if ($file_draf_tesis instanceof UploadedFile) {
            $rawPath = $file_draf_tesis->store('public/dokumen/seminarhasil');
            $path_draf_tesis = str_replace('public/', '', $rawPath);
        }

        $file_ppt = $request->file('tesis_ppt');
        if ($file_ppt instanceof UploadedFile) {
            $rawPath = $file_ppt->store('public/dokumen/seminarhasil');
            $path_ppt = str_replace('public/', '', $rawPath);
        }
        $file_dok_persetujuan_sem_hasil = $request->file('dok_persetujuan_sem_hasil');
        if ($file_dok_persetujuan_sem_hasil instanceof UploadedFile) {
            $rawPath = $file_dok_persetujuan_sem_hasil->store('public/dokumen/seminarhasil');
            $path_dok_persetujuan_sem_hasil = str_replace('public/', '', $rawPath);
        }

        $file_loa = $request->file('loa');
        if ($file_loa instanceof UploadedFile) {
            $rawPath = $file_loa->store('public/dokumen/seminarhasil');
            $path_loa = str_replace('public/', '', $rawPath);
        }

        $file_toefl = $request->file('toefl');
        if ($file_toefl instanceof UploadedFile) {
            $rawPath = $file_toefl->store('public/dokumen/seminarhasil');
            $path_toefl = str_replace('public/', '', $rawPath);
        }

        $file_plagiarisme = $request->file('plagiarisme');
        if ($file_plagiarisme instanceof UploadedFile) {
            $rawPath = $file_plagiarisme->store('public/dokumen/seminarhasil');
            $path_plagiarisme = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user();
        $seminarHasil = $this->seminarHasil->create([
            'title'         => 'SEMINAR HASIL ' . strtoupper($mahasiswa->name),
            'status'        => $this->seminarHasil::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa->id,
            'draf_tesis'    => $path_draf_tesis ?? null,
            'tesis_ppt'     => $path_ppt ?? null,
            'dok_persetujuan_sem_hasil'  => $path_dok_persetujuan_sem_hasil ?? null,
            'loa'           => $path_loa ?? null,
            'toefl'         => $path_toefl ?? null,
            'plagiarisme'   => $path_plagiarisme ?? null,
        ]);

        Logging::log("CREATE SEMINAR HASIL", $seminarHasil);
        return $seminarHasil;
    }

    /**
     * @param  \App\Http\Requests\SeminarHasilUpdateRequest  $request
     * @param  \App\Models\SeminarHasil  $seminar_hasil
     * @return \App\Models\SeminarHasil
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SeminarHasilUpdateRequest $request, SeminarHasil $seminar_hasil)
    {
        $input = $request->safe([
            'draf_tesis', 'tesis_ppt', 'dok_persetujuan_sem_hasil', 'loa', 'toefl', 'plagiarisme'
        ]);

        if ($seminar_hasil->status == SeminarHasil::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat diubah dikarenakan telah diapprove']);
        }

        $document = $request->file('draf_tesis');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_hasil->draf_tesis;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenameDraf = $document->store('public/dokumen/seminarhasil');
            $filenameDraf = str_replace('public/', '', $filenameDraf);
        } else {
            $filenameDraf = $seminar_hasil->draf_tesis;
        };

        $document = $request->file('tesis_ppt');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_hasil->tesis_ppt;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePPT = $document->store('public/dokumen/seminarhasil');
            $filenamePPT = str_replace('public/', '', $filenamePPT);
        } else {
            $filenamePPT = $seminar_hasil->tesis_ppt;
        };

        $document = $request->file('dok_persetujuan_sem_hasil');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_hasil->dok_persetujuan_sem_hasil;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePersetujuan = $document->store('public/dokumen/seminarhasil');
            $filenamePersetujuan = str_replace('public/', '', $filenamePersetujuan);
        } else {
            $filenamePersetujuan = $seminar_hasil->dok_persetujuan_sem_hasil;
        };

        $document = $request->file('loa');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_hasil->loa;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenameLoa = $document->store('public/dokumen/seminarhasil');
            $filenameLoa = str_replace('public/', '', $filenameLoa);
        } else {
            $filenameLoa = $seminar_hasil->loa;
        };

        $document = $request->file('plagiarisme');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_hasil->plagiarisme;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePlagiarisme = $document->store('public/dokumen/seminarhasil');
            $filenamePlagiarisme = str_replace('public/', '', $filenamePlagiarisme);
        } else {
            $filenamePlagiarisme = $seminar_hasil->plagiarisme;
        };

        $document = $request->file('toefl');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_hasil->toefl;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenameToefl = $document->store('public/dokumen/seminarhasil');
            $filenameToefl = str_replace('public/', '', $filenameToefl);
        } else {
            $filenameToefl = $seminar_hasil->toefl;
        };
        $seminar_hasil->update([
            'status'        => $this->seminarHasil::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'date'          => now(),
            'draf_tesis'  => $filenameDraf ?? $seminar_hasil->draf_tesis,
            'tesis_ppt'   => $filenamePPT ?? $seminar_hasil->tesis_ppt,
            'dok_persetujuan_sem_hasil'  => $filenamePersetujuan ?? $seminar_hasil->dok_persetujuan_sem_hasil,
            'loa'  => $filenameLoa ?? $seminar_hasil->loa,
            'plagiarisme'  => $filenamePlagiarisme ?? $seminar_hasil->plagiarisme,
            'toefl'  => $filenameToefl ?? $seminar_hasil->toefl,
        ]);

        Logging::log("EDIT SEMINAR HASIL", $seminar_hasil);
        return $seminar_hasil;
    }

    /**
     * @param  \App\Models\SeminarHasil  $seminarHasil
     * @return \App\Models\SeminarHasil
     */
    public function delete(SeminarHasil $seminar_hasil): bool
    {
        if ($seminar_hasil->status == SeminarHasil::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat dihapus dikarenakan telah diapprove']);
        }

        Logging::log("DELETE SEMINAR HASIL", $seminar_hasil);
        $seminarHasil = $seminar_hasil->delete();
        return $seminarHasil;
    }
}
