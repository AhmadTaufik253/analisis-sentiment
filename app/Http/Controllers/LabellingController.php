<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Labelling;

class LabellingController extends Controller
{
    public function index()
    {
        $labelling_data = Labelling::all();
        return view('labelling', compact('labelling_data'));
    }

    public function process()
    {
        try {
            $labellingScript = base_path('public/assets/py/labelling.py');
            $commandProses = escapeshellcmd("python " . $labellingScript);
            shell_exec($commandProses);
        } catch (\Throwable $th) {
            \Log::info("message: " . $th->getMessage());
        }
        return redirect()->back();
    }

    public function destroy()
    {
        Labelling::truncate();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
