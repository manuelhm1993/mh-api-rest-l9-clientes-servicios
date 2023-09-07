<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The services that belong to the client.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
