<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(){
        $videos = Video::query()->published()
            ->orderByDesc('created_at')->paginate(10);

        return response($videos, 200);
    }
}
