<?php

namespace App\Repositories\Mahasiswa;

use App\Contracts\Logging;
use App\Models\SeminarProyek;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SeminarProyekStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarProyekUpdateRequest;

class SeminarProyekRepository
{

    /**
     * @var \App\Models\SeminarProyek
     */
    protected $seminarProyek;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarProyek  $seminarProyek
     */
    public function __construct(
        SeminarProyek $seminarProyek,
    ) {
        $this->seminarProyek = $seminarProyek;
    }

    /**
     * @param  \App\Http\Requests\SeminarProyekStoreRequest  $request
     * @return \App\Models\SeminarProyek
     */
    public function store(SeminarProyekStoreRequest $request)
    {
        $input = $request->safe([
            'dok_per_sem_proyek'
        ]);

        $file_dok_per_sem_proyek = $request->file('dok_per_sem_proyek');
        if ($file_dok_per_sem_proyek instanceof UploadedFile) {
            $rawPath = $file_dok_per_sem_proyek->store('public/dokumen/seminarproyek');
            $path_dok_per_sem_proyek = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user();
        $seminarProyek = $this->seminarProyek->create([
            'title'         => 'SEMINAR PROYEK ' . strtoupper($mahasiswa->name),
            'status'        => $this->seminarProyek::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa->id,
            'dok_per_sem_proyek'  => $path_dok_per_sem_proyek ?? null,
        ]);

        Logging::log("CREATE SEMINAR PRAPROPOSAL", $seminarProyek);
        return $seminarProyek;
    }

    /**
     * @param  \App\Http\Requests\SeminarProyekUpdateRequest  $request
     * @param  \App\Models\SeminarProyek  $seminar_proyek
     * @return \App\Models\SeminarProyek
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SeminarProyekUpdateRequest $request, SeminarProyek $seminar_proyek)
    {
        $input = $request->safe([
            'dok_per_sem_proyek'
        ]);
        $document = $request->file('dok_per_sem_proyek');

        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $seminar_proyek->dok_per_sem_proyek;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenamePersetujuan = $document->store('public/dokumen/seminarproyek');
            $filenamePersetujuan = str_replace('public/', '', $filenamePersetujuan);
        } else {
            $filenamePersetujuan = $seminar_proyek->dok_per_sem_proyek;
        };

        $seminar_proyek->update([
            'dok_per_sem_proyek'  => $filenamePersetujuan ?? $seminar_proyek->dok_per_sem_proyek,
        ]);

        Logging::log("EDIT SEMINAR PRAPROPOSAL", $seminar_proyek);
        return $seminar_proyek;
    }

    /**
     * @param  \App\Models\SeminarProyek  $seminarProyek
     * @return \App\Models\SeminarProyek
     */
    public function delete(SeminarProyek $seminar_proyek): bool
    {
        if ($seminar_proyek->status == SeminarProyek::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Seminar tidak dapat dihapus dikarenakan telah diapprove']);
        }

        Logging::log("DELETE SEMINAR PRAPROPOSAL", $seminar_proyek);
        $seminarProyek = $seminar_proyek->delete();
        return $seminarProyek;
    }
}
