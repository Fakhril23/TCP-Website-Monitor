<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Imports\WebsiteImport;
use Maatwebsite\Excel\Facades\Excel;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $website = Website::all();
        return view('websites.index', compact('website'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('websites.create');
        //
    }

    /**
     * Store a newly created resource in storage.*/
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|array',
        'url' => 'required|array',
        'name.*' => 'required|string',
        'url.*' => 'required|string',
    ]);

    foreach ($request->name as $index => $name) {

        Website::create([
            'name' => $name,
            'url' => $request->url[$index],
            'status_http' => 'Belum Dicek',
            'status_https' => 'Belum Dicek',
            'status' => 'Belum Dicek',
        ]);
    }

    return redirect()->route('websites.index');
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:5120',
    ]);

    Excel::import(new WebsiteImport, $request->file('file'));

    return redirect()
        ->route('websites.index')
        ->with('success', 'Data website berhasil diimport.');
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
        $website = Website::findOrFail($id);

        $website->delete();

        return redirect()->route('websites.index');

        //
    }
}
