<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;

class SkpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Website::with(['testings' => function ($query) {
            $query->latest()->limit(20);
        }]);

        // Fitur Pencarian berdasarkan awalan huruf Nama Website
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', $search . '%');
        }

        // Fitur Filter berdasarkan Status (Online/Offline)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil 10 website per halaman dan pertahankan query string URL
        $websites = $query->paginate(10)->withQueryString();

        // Siapkan data grafik
        $websites->each(function ($website) {
            $testings = $website->testings->reverse()->values(); // urut lama -> baru

            $website->chart_labels = $testings->map(function ($testing) {
                return $testing->created_at->format('d/m H:i');
            })->toArray();

            $website->chart_response_times = $testings->pluck('response_time')->toArray();

            $website->chart_status_colors = $testings->map(function ($testing) {
                return match (strtolower((string) $testing->status)) {
                    'healthy' => '#34d399', // hijau
                    'partial' => '#fbbf24', // kuning
                    default => '#f87171',   // merah (down / status lain)
                };
            })->toArray();
        });

        return view('skpds.index', compact('websites'));
    }

    // Method lainnya dibiarkan seperti semula ...
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
