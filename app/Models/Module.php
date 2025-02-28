<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['title', 'order', 'content'];

    public function userProgress() {
        return $this->hasMany(UserProgress::class);
    }
}