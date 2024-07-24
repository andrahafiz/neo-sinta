<?php

namespace App\Repositories\Dosen;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\Product;
use App\Models\Thesis;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\ThesisCreateRequest;
use App\Http\Requests\ThesisUpdateRequest;
use App\Http\Requests\Dosen\ThesisRequest;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interface\ThesisInterface;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ThesisRepository
{

    /**
     * @var \App\Models\Thesis
     */
    protected $thesisModel;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @var \App\Models\Dosen
     */
    protected $mahasiswa;

    /**
     * @param  \App\Models\Thesis  $thesisModel
     */
    public function __construct(Thesis $thesisModel)
    {
        $this->thesisModel = $thesisModel;
        $this->mahasiswa = auth()->guard('dosen-guard')->user();
    }

    /**
     * @param  Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function approve(Thesis $thesis)
    {
        if ($thesis->dosen_thesis != auth()->user()->id)
            throw new UnauthorizedException(403, 'Unauthorized');

        $thesis->update([
            'approved_at' => now(),
        ]);

        return $thesis;
    }
}
