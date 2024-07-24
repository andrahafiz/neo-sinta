<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Bimbingan;
use App\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BimbinganResource;
use App\Http\Resources\BimbinganCollection;
use App\Repositories\Dosen\BimbinganRepository;
use App\Http\Requests\Dosen\BimbinganStoreRequest;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class BimbinganController extends Controller
{
    protected $bimbinganModel;
    protected $bimbingaRepository;

    public function __construct(
        Bimbingan $bimbinganModel,
        BimbinganRepository $bimbingaRepository
    ) {
        $this->bimbinganModel = $bimbinganModel;
        $this->bimbingaRepository = $bimbingaRepository;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $this->bimbinganModel->with('mahasiswa');

        if ($user->hasRole('kaprodi')) {
            // Jika role kaprodi, tampilkan semua data bimbingan
            $bimbingans = $query->orderByDesc('created_at')->paginate($request->query('show'));
        } else if ($user->hasRole('dosen_pembimbing')) {
            // Jika role dosen pembimbing, tx`ampilkan hanya data bimbingan milik dosen tersebut
            $query->whereHas('lecture', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
            $bimbingans = $query->orderByDesc('created_at')->paginate($request->query('show'));
        } else {
            throw new UnauthorizedException(403, 'Unauthorized');
        }

        return Response::json(new BimbinganCollection($bimbingans));
    }

    public function approve(Request $request, Bimbingan $bimbingan)
    {
        $newBimbingan = DB::transaction(function () use ($request, $bimbingan) {
            $newBimbingan = $this->bimbingaRepository
                ->approve($bimbingan);
            return $newBimbingan;
        });

        $newBimbingan->load(['mahasiswa', 'lecture']);
        return Response::json(new BimbinganResource($newBimbingan));
    }

    public function show(Bimbingan $bimbingan)
    {
        $bimbingan->load(['mahasiswa', 'lecture']);
        return Response::json(new BimbinganResource($bimbingan));
    }
}
