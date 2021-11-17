<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilledOrder extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
//
//    public function asset()
//    {
//        return $this->belongsTo(Asset::class);
//    }
}
