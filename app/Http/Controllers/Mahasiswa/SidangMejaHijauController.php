<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SidangMejaHijau;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SidangMejaHijauResource;
use App\Http\Resources\SidangMejaHijauCollection;
use App\Repositories\Mahasiswa\SidangMejaHijauRepository;
use App\Http\Requests\Mahasiswa\SidangMejaHijauStoreRequest;
use App\Http\Requests\Mahasiswa\SidangMejaHijauUpdateRequest;

class SidangMejaHijauController extends Controller
{
    /**
     * @var \App\Models\SidangMejaHijau
     */
    protected $sidangMejaHijauModel;

    /**
     * @var \App\Repositories\Mahasiswa\SidangMejaHijauRepository
     */
    protected $sidangMejaHijauRepository;

    protected $user;

    /**
     * @param  \App\Models\SidangMejaHijau  $sidangMejaHijauModel
     * @param  \App\Repositories\SidangMejaHijauRepository  $sidangMejaHijauRepository
     */
    public function __construct(
        SidangMejaHijau $sidangMejaHijauModel,
        SidangMejaHijauRepository $sidangMejaHijauRepository
    ) {
        $this->sidangMejaHijauModel = $sidangMejaHijauModel;
        $this->sidangMejaHijauRepository = $sidangMejaHijauRepository;
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $submission = $this->sidangMejaHijauModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->DataMahasiswa()
            ->paginate($request->query('show'));
        return Response::json(new SidangMejaHijauCollection($submission));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SidangMejaHijauStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SidangMejaHijauStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->sidangMejaHijauRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new SidangMejaHijauResource($submission),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SidangMejaHijau $sidang_meja_hijau)
    {
        $sidang_meja_hijau->load(['mahasiswa', 'lecture']);
        return Response::json(new SidangMejaHijauResource($sidang_meja_hijau));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SidangMejaHijauUpdateRequest  $request
     * @param  \App\Models\SidangMejaHijau $sidang_meja_hijau
     * @return \Illuminate\Http\Response
     */
    public function update(SidangMejaHijauUpdateRequest $request, SidangMejaHijau $sidang_meja_hijau)
    {
        $updatedSidangMejaHijau = DB::transaction(function () use ($request, $sidang_meja_hijau) {
            $updatedSidangMejaHijau = $this->sidangMejaHijauRepository
                ->update($request, $sidang_meja_hijau);
            return $updatedSidangMejaHijau;
        });
        return Response::json(new SidangMejaHijauResource($updatedSidangMejaHijau));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SidangMejaHijau $sidang_meja_hijau)
    {
        $deleteSidangMejaHijau = DB::transaction(function () use ($sidang_meja_hijau) {
            $deleteSidangMejaHijau = $this->sidangMejaHijauRepository
                ->delete($sidang_meja_hijau);
            return $deleteSidangMejaHijau;
        });

        return Response::noContent();
    }
}
