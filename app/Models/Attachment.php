<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Attachment extends Model
{
    use LogsActivity;
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
