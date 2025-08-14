<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preprocessing;
use Illuminate\Support\Facades\Log;

class PreprocessingController extends Controller
{
    // function index untuk memperoses load awal halaman
    public function index()
    {
        $preprocessing_data = Preprocessing::select('real_text', 'clean_text')->get();
        return view('preprocessing', compact('preprocessing_data'));
    }

    // function untuk memproses data
    public function process()
    {
        set_time_limit(300);
        try {
            $processing = base_path('public\assets\py\preprocessing.py');
            $commandProses = escapeshellcmd("python " . $processing);
            shell_exec($commandProses);
        } catch (\Throwable $th) {
            Log::info("message: " . $th->getMessage());
        }
        return redirect()->back();
    }

    // function destroy untuk menghapus data
    public function destroy()
    {
        Preprocessing::truncate();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
