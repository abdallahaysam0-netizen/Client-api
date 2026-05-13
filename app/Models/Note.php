<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Note extends Model
{
    use LogsActivity;
    protected $fillable = [
        'client_id',
        'note',
    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
