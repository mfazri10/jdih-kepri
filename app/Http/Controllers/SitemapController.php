<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>', 200)
            ->header('Content-Type', 'text/xml');
    }
}
