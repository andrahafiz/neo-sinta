<?php

namespace App\Repositories\Mahasiswa;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\TitleSubmission;
use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\TitleSubmissionCreateRequest;
use App\Http\Requests\TitleSubmissionUpdateRequest;
use App\Repositories\Interface\TitleSubmissionInterface;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\TitleSubmissionRequest;
use App\Http\Requests\Mahasiswa\TitleSubmissionCheckOutRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TitleSubmissionRepository
{

    /**
     * @var \App\Models\TitleSubmission
     */
    protected $sitInModel;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\TitleSubmission  $sitInModel
     */
    public function __construct(
        TitleSubmission $sitInModel,
    ) {
        $this->sitInModel = $sitInModel;
    }


    // /**
    //  * @param  \App\Http\Requests\TitleSubmissionUpdateRequest  $request
    //  * @param  \App\Models\TitleSubmission  $checkin
    //  * @return \App\Models\TitleSubmission
    //  * @throws \Illuminate\Validation\ValidationException
    //  */
    // public function update(TitleSubmissionUpdateRequest $request, TitleSubmission $checkin)
    // {
    //     $input = $request->safe([
    //         'name',
    //         'cycleTime',
    //     ]);

    //     $checkin->update([
    //         'name'          => $input['name'] ?? $checkin->name,
    //         'cycle_time'    => $input['cycleTime'] ?? $checkin->cycle_time,
    //     ]);


    //     return $checkin;
    // }

    // /**
    //  * @param  \App\Contracts\Request  $request
    //  * @param  \App\Models\TitleSubmission  $checkin
    //  * @return \App\Models\TitleSubmission
    //  */
    // public function delete(Request $request, TitleSubmission $checkin)
    // {
    //     $checkin->delete();


    //     return $checkin;
    // }
}
