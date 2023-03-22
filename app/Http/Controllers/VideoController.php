<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $videos = Video::all();

        return view('video.index', compact('videos'));
    }

    public function show(Request $request, Video $video): View
    {
        return view('video.show', compact('video'));
    }
}
