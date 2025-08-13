<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    
    protected $table = 'result';
    public $timestamps = false;
    protected $fillable = [
        'created_at',
        'data_training',
        'data_training_positive',
        'data_training_negative',
        'data_training_netral',
        'data_testing',
        'tp_positive',
        'fp_positive',
        'fn_positive',
        'tp_negative',
        'fp_negative',
        'fn_negative',
        'tp_netral',
        'fp_netral',
        'fn_netral',
        'predict_positive',
        'predict_negative',
        'predict_netral',
        'vocabulary',
        'vocab_weight',
    ];
}
