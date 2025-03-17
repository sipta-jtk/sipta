<?php

namespace App\Modules\Repository\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use App\Models\Laporan;

class RepositoryController extends Controller
{
    public function index(): View
    {
        return view('Repository.views.view');
    }

    public function home(): View
    {
        return view('Repository.views.home');
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        $results = Laporan::where('judul', 'LIKE', "%{$query}%")
                          ->orWhere('versi', 'LIKE', "%{$query}%")
                          ->get();

        return response()->json($results);
    }

    public function list()
    {
        // Ambil semua data laporan dari database
        $laporan = Laporan::all();

        // Kirim data ke view
        return view('repository::view', compact('laporan'));
    }
}