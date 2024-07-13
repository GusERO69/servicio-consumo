<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;
    protected $table = 'results';

    protected $fillable = [
        'user_id',
        'prediction',
        'date_week',
        'higher',
        'probability',
        'error',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
