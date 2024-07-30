<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Thesis;
use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\AssigPembimbingRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\ThesisResource;
use App\Repositories\Dosen\ThesisRepository;
use Psy\Output\Theme;

class ThesisController extends Controller
{
    protected $thesisModel;
    protected $thesisRepository;

    public function __construct(
        Thesis $thesisModel,
        ThesisRepository $thesisRepository
    ) {
        $this->thesisModel = $thesisModel;
        $this->thesisRepository = $thesisRepository;
    }

    public function index(Request $request)
    {
        $pembimbing = QueryBuilder::for(Thesis::class)
            ->allowedFilters([
                AllowedFilter::partial('mahasiswa', 'mahasiswa.name'),
                AllowedFilter::partial('pembimbing1', 'pembimbing1.name'),
                AllowedFilter::partial('pembimbing2', 'pembimbing2.name'),
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('show'));

        return Response::json(ThesisResource::collection($pembimbing));
    }

    public function assign_pembimbing(AssigPembimbingRequest $request)
    {
        $data = $request->all();

        $this->nonActiveThesis($data['mahasiswa']);
        $pembimbing = Thesis::create(
            [
                'judul_thesis' => $data['judul'],
                'konsentrasi_ilmu' => $data['konsentrasi_ilmu'],
                'deskripsi'     => $data['deskripsi'],
                'mahasiswas_id' => $data['mahasiswa'],
                'pembimbing_1'  => $data['pembimbing_1'],
                'pembimbing_2'  => $data['pembimbing_2'],
                'is_active'     => 1
            ]
        );

        return Response::json(new ThesisResource($pembimbing));
    }

    private function nonActiveThesis($mahasiswa)
    {
        Thesis::where('mahasiswas_id', $mahasiswa)->update(
            ['is_active' => 0]
        );
    }
}
