<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $fillable = [
        'from', 'to', 'text'
    ];

    public function fromContact(){
        return $this->belongsTo(User, 'id', 'from');
    }
}
