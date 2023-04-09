<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ArchiveResource;
use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(int $page = 0)
    {
        return ArchiveResource::collection(Archive::paginate($page));
    }
}
