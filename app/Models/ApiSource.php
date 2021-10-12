<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSource extends Model
{
    use HasFactory;

    protected $hidden = [
        'url',
        'query_string',
        'key_prefix',
        'news_structure'
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'source_id');
    }

    public function queries()
    {
        return $this->belongsToMany(Query::class, 'api_source_queries', 'source_id');
    }
}
