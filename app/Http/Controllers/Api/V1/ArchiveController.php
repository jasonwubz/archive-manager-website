<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveStoreRequest;
use App\Http\Resources\V1\ArchiveResource;
use App\Models\Archive;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function index(int $page = 0)
    {
        return ArchiveResource::collection(Archive::paginate(10));
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

    public function destroy(Archive $archive)
    {
        // it's possible for files to be orphaned if Storage::delete doesn't work for some reason
        Storage::delete([$archive->path]);
        $archive->delete();

        // returning dirty model
        return response(['data' => $archive], Response::HTTP_ACCEPTED);
    }

    public function download(Archive $archive)
    {
        $archive->times_downloaded++;
        $archive->update();

        return Storage::download($archive->path, $archive->original_name);
    }
}
