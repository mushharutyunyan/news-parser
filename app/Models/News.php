<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public function queryRow()
    {
        return $this->belongsTo(Query::class,'query_id');
    }

    public function source()
    {
        return $this->belongsTo(ApiSource::class);
    }
}
