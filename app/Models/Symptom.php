<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::saved(function (Symptom $symptom) {
            $symptom->plausability = 1 - $symptom->probability;
            $symptom->saveQuietly();
        });
    }
}
