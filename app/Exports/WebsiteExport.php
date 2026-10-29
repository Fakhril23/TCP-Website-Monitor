<?php

namespace App\Exports;

use App\Models\Website;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WebsiteExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return Website::all();
    }

    /**
     * Menentukan Judul Kolom di Excel (Pastikan jumlahnya 7 sesuai map)
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Website',
            'URL',
            'Status HTTP',
            'Status HTTPS',
            'Status',
            'Last Check',
        ];
    }

    /**
     * Memetakan data per baris
     */
    public function map($website): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $website->name,
            $website->url,
            $website->status_http,
            $website->status_https,
            $website->status,
            $website->last_check,
        ];
    }
}
