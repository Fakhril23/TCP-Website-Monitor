<?php

namespace App\Imports;

use App\Models\Website;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WebsiteImport implements ToModel, WithHeadingRow
{
    public function model(array $row): ?Website
    {
        // Cek apakah URL sudah ada
        $existingWebsite = Website::where('url', $row['url'])->first();

        // Kalau sudah ada, lewati
        if ($existingWebsite) {
            return null;
        }

        // Kalau belum ada, tambahkan
        return new Website([
            'name' => $row['nama_website'],
            'url' => $row['url'],
            'status_http' => 'Belum Dicek',
            'status_https' => 'Belum Dicek',
            'status' => 'Belum Dicek',
        ]);
    }
}
