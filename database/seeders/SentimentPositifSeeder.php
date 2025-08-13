<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SentimentPositifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/sentiment_positif.json'));
        $data = json_decode($json, true);

        // Hapus kolom ID jika tidak digunakan (karena auto increment)
        foreach ($data as &$item) {
            unset($item['id']);
        }

        DB::table('sentiment_positif')->insert($data);
    }
}
