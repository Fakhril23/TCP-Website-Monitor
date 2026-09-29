<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Testing;
use Illuminate\Support\Facades\DB;
class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $websites = Website::all();

        return view('testings.index', compact('websites'));
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
public function check(Request $request)
{
    // Ambil semua website yang terdaftar
    $websites = Website::all();

    $results = [];

    foreach ($websites as $websiteItem) {

        // Hilangkan http:// atau https:// jika ada
        $host = preg_replace('#^https?://#', '', $websiteItem->url);

        // Hilangkan slash di belakang
        $host = rtrim($host, '/');

        $timeout = 5;

        // Mulai hitung waktu respons dari sini, sebelum kedua port dicek
        $checkStartedAt = microtime(true);

        // Cek HTTP port 80
        $fp80 = @stream_socket_client(
            "tcp://" . $host . ":80",
            $errno80,
            $errstr80,
            $timeout
        );

        // Cek HTTPS port 443
        $fp443 = @stream_socket_client(
            "tcp://" . $host . ":443",
            $errno443,
            $errstr443,
            $timeout
        );

        // Total waktu yang dibutuhkan untuk mengecek kedua port, dalam milidetik
        $responseTime = (int) round((microtime(true) - $checkStartedAt) * 1000);

        $statusHttp = $fp80 ? "Online" : "Offline";
        $statusHttps = $fp443 ? "Online" : "Offline";


        // Tentukan status keseluruhan
        if ($statusHttp == "Online" && $statusHttps == "Online") {

            $status = "Healthy";
            $statusColor = "🟢";

        } elseif ($statusHttp == "Online" || $statusHttps == "Online") {

            $status = "Partial";
            $statusColor = "🟡";

        } else {

            $status = "Down";
            $statusColor = "🔴";
        }


        // Tutup koneksi
        if ($fp80) {
            fclose($fp80);
        }

        if ($fp443) {
            fclose($fp443);
        }
    // Update tabel websites
    $websiteItem->update([
        'status_http' => $statusHttp,
        'status_https' => $statusHttps,
        'status' => $status,
        'last_check' => now(),
    ]);
        //simpan hasil  testing
    Testing::create([
        'website_id' => $websiteItem->id,
        'status_http' => $statusHttp,
        'status_https' => $statusHttps,
        'status' => $status,
        'response_time' => $responseTime,
        'timestamp' => now(),
    ]);

        // Masukkan hasil ke array
        $results[] = [
            'website' => $websiteItem->url,
            'http' => $statusHttp,
            'https' => $statusHttps,
            'status' => $status,
            'statusColor' => $statusColor,
            'response_time' => $responseTime,
        ];
    }

    // WAKTUNYA RETURN SETELAH SEMUA WEBSITE SELESAI DITES
    return view('testings.index', [
        'websites' => $websites,
        'results' => $results,
        'check_date' => now()->format('d M Y'),
        'check_time' => now()->format('H:i:s'),
        'last_check' => now()->format('Y-m-d H:i:s'),
    ]);
}
public function save(Request $request)
{
    $results = $request->input('results', []);

    $timestamp = $request->input('timestamp');

    foreach ($results as $result) {

        $website = Website::where('url', $result['website'])->first();

        if (!$website) {
            continue;
        }

        // Update tabel websites
        $website->update([
            'status_http' => $result['http'],
            'status_https' => $result['https'],
            'status' => $result['status'],
            'last_check' => $timestamp,
        ]);

        // Simpan ke tabel testings
        Testing::create([
            'website_id' => $website->id,
            'status_http' => $result['http'],
            'status_https' => $result['https'],
            'status' => $result['status'],
            'response_time' => $result['response_time'] ?? null,
            'timestamp' => $timestamp,
        ]);
    }

    return redirect()
        ->route('testings.index')
        ->with('success', 'Hasil testing berhasil disimpan.');
}
}
