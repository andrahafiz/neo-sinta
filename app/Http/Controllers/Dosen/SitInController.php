<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Sitin;
use App\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SitInResource;
use App\Http\Resources\SitInCollection;
use App\Repositories\Dosen\SitInRepository;
use App\Http\Requests\Dosen\SitInCheckInRequest;
use App\Http\Requests\Dosen\SitInCheckOutRequest;
use App\Http\Requests\Dosen\SitInUpdateRequest;

class SitInController extends Controller
{

    /**
     * @var \App\Models\SitIn
     */
    protected $sitInModel;

    /**
     * @var \App\Repositories\Dosen\SitInRepository
     */
    protected $sitInRepository;

    /**
     * @param  \App\Models\SitIn  $sitInModel
     * @param  \App\Repositories\Dosen\SitInRepository  $sitInRepository
     */
    public function __construct(
        Sitin $sitInModel,
        SitInRepository $sitInRepository
    ) {
        $this->sitInModel = $sitInModel;
        $this->sitInRepository = $sitInRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sitInes = $this->sitInModel->with('mahasiswa')
            ->orderByDesc('created_at')->paginate($request->query('show'));

        return Response::json(new SitInCollection($sitInes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SitInUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(SitInUpdateRequest $request, Sitin $sitin)
    {
        $newSitIn = DB::transaction(function () use ($request, $sitin) {
            $newSitIn = $this->sitInRepository
                ->update($request, $sitin);

            return $newSitIn;
        });
        $newSitIn->load(['mahasiswa']);
        return Response::okUpdated(new SitInResource($newSitIn));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, Sitin $sitIn)
    {
        $newSitIn = DB::transaction(function () use ($request, $sitIn) {
            $newSitIn = $this->sitInRepository
                ->confirm($request, $sitIn);

            return $newSitIn;
        });
        $newSitIn->load(['mahasiswa']);
        return Response::okUpdated(new SitInResource($newSitIn));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SitIn  $sitIn
     * @return \Illuminate\Http\Response
     */
    public function show(SitIn $sitin)
    {
        $sitin->load(['mahasiswa']);

        return Response::json(new SitInResource($sitin));
    }
}
