<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Note extends Model
{
    use HasFactory, LogsActivity;
    protected $with = ['client'];
    protected $fillable = [
        'client_id',
        'note',
    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
