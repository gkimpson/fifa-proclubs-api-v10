<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $data['videos'] = Video::all();

        return view('video.index', $data);
    }

    public function show(Request $request, Video $video): View
    {
        $data['video'] = $video;

        return view('video.show', $data);
    }
}
