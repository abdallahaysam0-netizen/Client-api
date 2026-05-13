<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
    ];
    public function notes(){
        return $this->hasMany(Note::class);
    }
    public function attachments(){
        return $this->hasMany(Attachment::class);
    }
}
