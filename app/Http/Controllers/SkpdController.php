<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;

class SkpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil 20 hasil testing paling baru per website untuk bahan grafik
        $websites = Website::with(['testings' => function ($query) {
            $query->latest()->limit(20);
        }])->get();

        // Siapkan data grafik: label waktu, response time, dan warna titik (hijau = up, merah = down)
        // CATATAN: nama kolom di bawah ini (status, response_time, created_at) adalah ASUMSI.
        // Sesuaikan dengan nama kolom asli di tabel/model Testing kamu kalau berbeda.
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
