<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recomendation extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'disease';
    protected $fillable = ['disease', 'recomendation'];

    public function symptom() : BelongsTo {
        return $this->belongsTo(Symptom::class);
    }
}
