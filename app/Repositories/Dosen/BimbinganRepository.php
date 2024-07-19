<?php

namespace App\Repositories\Dosen;

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
use App\Http\Requests\Dosen\BimbinganRequest;
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
     * @var \App\Models\Dosen
     */
    protected $mahasiswa;

    /**
     * @param  \App\Models\Bimbingan  $bimbinganModel
     */
    public function __construct(Bimbingan $bimbinganModel)
    {
        $this->bimbinganModel = $bimbinganModel;
        $this->mahasiswa = auth()->guard('dosen-guard')->user();
    }

    /**
     * @param  Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function approve(Request $request, Bimbingan $bimbingan)
    {

        $bimbingan = $bimbingan->update([
            'approved_at' => now(),
        ]);

        return $bimbingan;
    }
}
