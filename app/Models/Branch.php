<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'city', 'address'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
