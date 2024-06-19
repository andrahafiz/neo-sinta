<?php

namespace App\Repositories\Mahasiswa;

use App\Contracts\Logging;
use App\Models\SidangMejaHijau;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SidangMejaHijauStoreRequest;
use App\Http\Requests\Mahasiswa\SidangMejaHijauUpdateRequest;

class SidangMejaHijauRepository
{

    /**
     * @var \App\Models\SidangMejaHijau
     */
    protected $sidangMejaHijau;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SidangMejaHijau  $sidangMejaHijau
     */
    public function __construct(
        SidangMejaHijau $sidangMejaHijau,
    ) {
        $this->sidangMejaHijau = $sidangMejaHijau;
    }

    /**
     * @param  \App\Http\Requests\SidangMejaHijauStoreRequest  $request
     * @return \App\Models\SidangMejaHijau
     */
    public function store(SidangMejaHijauStoreRequest $request)
    {
        $input = $request->safe([
            'dok_persetujuan_sidang_meja_hijau'
        ]);

        $file_dok_persetujuan_sidang_meja_hijau = $request->file('dok_persetujuan_sidang_meja_hijau');
        if ($file_dok_persetujuan_sidang_meja_hijau instanceof UploadedFile) {
            $rawPath = $file_dok_persetujuan_sidang_meja_hijau->store('public/dokumen/sidangmejahijau');
            $path_dok_persetujuan_sidang_meja_hijau = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user();
        $sidangMejaHijau = $this->sidangMejaHijau->create([
            'title'         => 'SIDANG MEJA HIJAU ' . strtoupper($mahasiswa->name),
            'status'        => $this->sidangMejaHijau::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa->id,
            'dok_persetujuan_sidang_meja_hijau'  => $path_dok_persetujuan_sidang_meja_hijau ?? null,
        ]);

        Logging::log("CREATE SIDANG MEJA HIJAU", $sidangMejaHijau);
        return $sidangMejaHijau;
    }

    /**
     * @param  \App\Http\Requests\SidangMejaHijauUpdateRequest  $request
     * @param  \App\Models\SidangMejaHijau  $sidang_meja_hijau
     * @return \App\Models\SidangMejaHijau
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SidangMejaHijauUpdateRequest $request, SidangMejaHijau $sidang_meja_hijau)
    {
        $input = $request->safe([
            'dok_persetujuan_sidang_meja_hijau'
        ]);
        if ($sidang_meja_hijau->status == SidangMejaHijau::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Data sidang tidak dapat diubah dikarenakan telah diapprove']);
        }
        $document = $request->file('dok_persetujuan_sidang_meja_hijau');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/public/' . $sidang_meja_hijau->dok_persetujuan_sidang_meja_hijau;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filenameDraf = $document->store('public/dokumen/sidangmejahijau');
            $filenameDraf = str_replace('public/', '', $filenameDraf);
        } else {
            $filenameDraf = $sidang_meja_hijau->dok_persetujuan_sidang_meja_hijau;
        };

        $sidang_meja_hijau->update([
            'dok_persetujuan_sidang_meja_hijau'  => $filenameDraf ?? $sidang_meja_hijau->dok_persetujuan_sidang_meja_hijau,
        ]);

        Logging::log("EDIT SEMINAR SIDANG MEJA HIJAU", $sidang_meja_hijau);
        return $sidang_meja_hijau;
    }

    /**
     * @param  \App\Models\SidangMejaHijau  $sidangMejaHijau
     * @return \App\Models\SidangMejaHijau
     */
    public function delete(SidangMejaHijau $sidang_meja_hijau): bool
    {
        if ($sidang_meja_hijau->status == SidangMejaHijau::STATUS_APPROVE) {
            throw ValidationException::withMessages(['message' => 'Data sidang tidak dapat dihapus dikarenakan telah diapprove']);
        }

        Logging::log("DELETE SEMINAR SIDANG MEJA HIJAU", $sidang_meja_hijau);
        $sidangMejaHijau = $sidang_meja_hijau->delete();
        return $sidangMejaHijau;
    }
}
