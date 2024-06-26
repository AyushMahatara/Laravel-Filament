<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_number', 'relation_type'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class);
    }
}
