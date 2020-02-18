<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Art;
use DB;
use Spatie\Browsershot\Browsershot;

class DebugController extends Controller
{
    public function index()
    {
        Browsershot::url('https://www.google.com/')->save("");
    }

    private function createArt()
    {
        $controller = new ArtsController();
        $art = $controller->create(request()->merge([
            "name"    => "teste 123",
            "tags"    => ["teste"],
            "fields"  => ["teste"],
            "content" => ["teste" => 123],
            "json"    => ["teste" => 123],
        ]));
        return $art;
    }

    private function updateArt()
    {
        $controller = new ArtsController();
        $art = $controller->edit(1, request()->merge([
            "name"    => "teste 123 editado",
        ]));
        return $art;
    }

    private function createExpiredArts()
    {
        $controller = new ArtsController();
        $controller->cleanExpired();
    }
}
