<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calculation extends Model
{
    protected $guarded = ['id'];

    public function questionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class);
    }
}
