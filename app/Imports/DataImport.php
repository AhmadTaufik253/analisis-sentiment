<?php

namespace App\Imports;

use App\Models\ImportData;
use Maatwebsite\Excel\Concerns\ToModel;

class DataImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Abaikan baris header
        if ($row[0] === 'conversation_id_str') {
            return null;
        }

        return new ImportData([
            'conversation_id_str'         => $row[0],
            'created_at'                  => $row[1],
            'favorite_count'              => $row[2],
            'full_text'                   => $row[3],
            'id_str'                      => $row[4],
            'image_url'                   => $row[5],
            'in_reply_to_screen_name'     => $row[6],
            'lang'                        => $row[7],
            'location'                    => $row[8],
            'quote_count'                 => $row[9],
            'reply_count'                 => $row[10],
            'retweet_count'               => $row[11],
            'tweet_url'                   => $row[12],
            'user_id_str'                 => $row[13],
            'username'                    => $row[14],
        ]);
    }
}
