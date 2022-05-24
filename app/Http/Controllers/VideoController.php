<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index(){
        $videos = Video::query()->published()
            ->orderByDesc('created_at')->paginate(10);

        return response($videos, 200);
    }

    public function store(Request $request){

        $postData = $this->validate($request, [
            'url' => ['required', 'url'],
            'description' => ['sometimes'],
        ]);

        $desc = $request->has('description')
            ? $request->input('description') : '';

        $video = Video::create([
           'url' => $postData['url'],
           'description' => $desc,
           'user_id' => Auth::user()->id,
           'type' => 'youtube',
           'is_published' => 0,
        ]);

        return response($video, 201);
    }
}
