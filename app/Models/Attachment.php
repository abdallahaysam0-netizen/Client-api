<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Attachment extends Model
{
    use HasFactory, LogsActivity;
    protected $with = ['client'];
    protected $fillable=[
    "client_id",
    "file_name",
    "file_path",
    "file_type",
    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
