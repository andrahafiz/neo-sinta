<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Mahasiswa;
use App\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Http\Resources\MahasiswaCollection;
use App\Repositories\Dosen\MahasiswaRepository;
use App\Http\Requests\Dosen\MahasiswaStoreRequest;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class MahasiswaController extends Controller
{
    protected $mahasiswaModel;

    public function __construct(
        Mahasiswa $mahasiswaModel,
    ) {
        $this->mahasiswaModel = $mahasiswaModel;
    }

    public function index(Request $request)
    {
        $mahasiswas = $this->mahasiswaModel->all();
        return Response::json(new MahasiswaCollection($mahasiswas));
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['theses']);
        return Response::json(new MahasiswaResource($mahasiswa));
    }
}
