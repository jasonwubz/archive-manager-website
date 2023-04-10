<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveStoreRequest;
use App\Http\Resources\V1\ArchiveResource;
use App\Models\Archive;
use Symfony\Component\HttpFoundation\Response;

class ArchiveController extends Controller
{
    public function index(int $page = 0)
    {
        return ArchiveResource::collection(Archive::paginate($page));
    }

    public function store(ArchiveStoreRequest $request)
    {
        $archiveFile = $request->file('archive');

        $archive = Archive::create([
            'path' => $archiveFile->store('archives'),
            'md5_checksum' => md5($archiveFile->get()),
            'original_name' => $archiveFile->getClientOriginalName(),
            'size' => $archiveFile->getSize(),
        ]);

        return response(['data' => $archive], Response::HTTP_CREATED);
    }
}
