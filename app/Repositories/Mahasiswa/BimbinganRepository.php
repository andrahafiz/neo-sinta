<?php

namespace App\Repositories\Mahasiswa;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\Bimbingan;
use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\BimbinganCreateRequest;
use App\Http\Requests\BimbinganUpdateRequest;
use App\Repositories\Interface\BimbinganInterface;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\BimbinganStoreRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BimbinganRepository
{

    /**
     * @var \App\Models\Bimbingan
     */
    protected $bimbinganModel;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @var \App\Models\Mahasiswa
     */
    protected $mahasiswa;

    /**
     * @param  \App\Models\Bimbingan  $bimbinganModel
     */
    public function __construct(Bimbingan $bimbinganModel)
    {
        $this->bimbinganModel = $bimbinganModel;
        $this->mahasiswa = auth()->guard('mahasiswa-guard')->user();
    }

    /**
     * @param  \App\Http\Requests\Mahasiswa\BimbinganStoreRequest  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(BimbinganStoreRequest $request)
    {
        $input = $request->safe([
            'pembahasan', 'catatan', 'tanggal_bimbingan', 'dosen_pembimbing', 'type_pembimbing', 'bimbingan_type'
        ]);

        $bimbingan = $this->bimbinganModel->create([
            'pembahasan' => $input['pembahasan'],
            'catatan' => $input['catatan'],
            'mahasiswas_id' => $this->mahasiswa->id,
            'tanggal_bimbingan' => $input['tanggal_bimbingan'],
            'dosen_pembimbing' => $input['dosen_pembimbing'],
            'type_pembimbing' => $input['type_pembimbing'],
            'bimbingan_type' => $input['bimbingan_type'],
        ]);

        return $bimbingan;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bimbingan  $bimbingan
     * @return \App\Models\Bimbingan
     */
    public function delete(Request $request, Bimbingan $bimbingan)
    {
        $bimbingan->delete();
        return $bimbingan;
    }
}
