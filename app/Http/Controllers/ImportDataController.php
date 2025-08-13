<?php

namespace App\Http\Controllers;
use App\Models\ImportData;
use App\Imports\DataImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class ImportDataController extends Controller
{
    public function index()
    {
        $raw_data = ImportData::all();
        return view('import-data', compact('raw_data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new DataImport, $request->file('file'));

            return back()->with('success', 'Berhasil import data.');
        } catch (\Exception $e) {
            Log::info("message: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy()
    {
        ImportData::truncate();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
