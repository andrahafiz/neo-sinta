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
use App\Http\Requests\Dosen\BimbinganCheckInRequest;
use App\Http\Requests\Dosen\BimbinganCheckOutRequest;
use Illuminate\Validation\ValidationException;

class BimbinganController extends Controller
{

    /**
     * @var \App\Models\Bimbingan
     */
    protected $bimbinganModel;

    /**
     * @var \App\Repositories\Dosen\BimbinganRepository
     */
    protected $bimbingaRepository;

    /**
     * @param  \App\Models\Bimbingan  $bimbinganModel
     * @param  \App\Repositories\Dosen\BimbinganRepository  $bimbingaRepository
     */
    public function __construct(
        Bimbingan $bimbinganModel,
        BimbinganRepository $bimbingaRepository
    ) {
        $this->bimbinganModel = $bimbinganModel;
        $this->bimbingaRepository = $bimbingaRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bimbingaes = $this->bimbinganModel
            ->with('mahasiswa')
            ->DataDosen()
            ->Type(Bimbingan::SEMINAR_PRAPROPOSAL)
            ->orderByDesc('created_at')
            ->paginate($request->query('show'));

        return Response::json(new BimbinganCollection($bimbingaes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\BimbinganStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function approve(BimbinganStoreRequest $request)
    {
        $newBimbingan = DB::transaction(function () use ($request) {
            $newBimbingan = $this->bimbingaRepository
                ->approve($request);

            return $newBimbingan;
        });
        $newBimbingan->load(['mahasiswa', 'lecture']);

        return Response::json(
            new BimbinganResource($newBimbingan),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bimbingan  $bimbinga
     * @return \Illuminate\Http\Response
     */
    public function show(Bimbingan $bimbingan)
    {
        $bimbingan->load(['mahasiswa', 'lecture']);

        return Response::json(new BimbinganResource($bimbingan));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bimbingan  $bimbinga
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Bimbingan $bimbingan)
    {
        if ($bimbingan->approved_at != null)
            throw ValidationException::withMessages(['message' => 'Bimbingan sudah di approve, tidak dapat dihapus']);

        $deletedBimbingan = DB::transaction(function () use ($request, $bimbingan) {
            $deletedBimbingan = $this->bimbingaRepository
                ->delete($request, $bimbingan);

            return $deletedBimbingan;
        });

        return Response::noContent();
    }
}
