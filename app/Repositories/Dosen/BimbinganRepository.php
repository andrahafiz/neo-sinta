<?php

namespace App\Repositories\Dosen;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\Product;
use App\Models\Bimbingan;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\BimbinganCreateRequest;
use App\Http\Requests\BimbinganUpdateRequest;
use App\Http\Requests\Dosen\BimbinganRequest;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interface\BimbinganInterface;
use Spatie\Permission\Exceptions\UnauthorizedException;
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
    public function approve(Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_pembimbing != auth()->user()->id)
            throw new UnauthorizedException(403, 'Unauthorized');

        $bimbingan->update([
            'approved_at' => now(),
        ]);

        return $bimbingan;
    }
}
