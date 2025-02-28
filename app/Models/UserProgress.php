<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = ['user_id', 'module_id', 'is_completed'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}