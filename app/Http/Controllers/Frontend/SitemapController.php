<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\BatDongSan;
use App\Models\DuAn;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $baiViets = BaiViet::where('hien_thi', 1)->latest('thoi_diem_dang')->get();
        $batDongSans = BatDongSan::where('hien_thi', 1)->latest()->get();
        $duAns = DuAn::where('hien_thi', 1)->latest()->get();

        $content = view('frontend.sitemap.index', compact('baiViets', 'batDongSans', 'duAns'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
