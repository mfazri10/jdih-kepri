<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function berita()
    {
        return 'Halaman Berita';
    }

    public function beritaShow($slug)
    {
        return 'Detail Berita: ' . $slug;
    }

    public function search()
    {
        return response()->json([]);
    }

    public function anggota()
    {
        return 'Halaman Anggota';
    }

    public function akd()
    {
        return 'Halaman AKD';
    }

    public function akdShow($slug)
    {
        return 'Detail AKD: ' . $slug;
    }

    public function fraksi()
    {
        return 'Halaman Fraksi';
    }

    public function agenda()
    {
        return 'Halaman Agenda';
    }

    public function pengumuman()
    {
        return 'Halaman Pengumuman';
    }

    public function aspirasi()
    {
        return 'Halaman Aspirasi';
    }

    public function aspirasiStore(Request $request)
    {
        return back()->with('success', 'Aspirasi berhasil disimpan.');
    }

    public function kunjungan()
    {
        return 'Halaman Kunjungan';
    }

    public function kunjunganStore(Request $request)
    {
        return back()->with('success', 'Kunjungan berhasil disimpan.');
    }

    public function getRegencies($province_id)
    {
        return response()->json([]);
    }

    public function jdih()
    {
        return 'Halaman JDIH';
    }

    public function jdihShow($slug)
    {
        return 'Detail JDIH: ' . $slug;
    }

    public function jdihDownload($slug)
    {
        return 'Download JDIH: ' . $slug;
    }

    public function page($slug)
    {
        return 'Halaman Statis: ' . $slug;
    }
}
