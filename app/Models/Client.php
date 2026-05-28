<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory, LogsActivity;
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
