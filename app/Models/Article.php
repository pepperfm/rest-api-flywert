<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public function getUser(User $user) // : bool|null
    {
        if (!$this) {
            return null; // сомнительно в целом возвращать null
        }

        return $user->isAuthor($this);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
