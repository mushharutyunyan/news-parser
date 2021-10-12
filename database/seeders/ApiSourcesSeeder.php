<?php

namespace Database\Seeders;

use App\Models\ApiSource;
use Illuminate\Database\Seeder;

class ApiSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiSource::create(
            [
                'name' => 'News API',
                'url' => 'https://newsapi.org/v2/everything',
                'query_string' => '?q={$query}&from={$date_from}&sortBy=publishedAt&apiKey={$key}',
                'key_prefix' => 'NEWSAPI',
                'news_structure' => json_encode(
                    [
                        'creator' => 'source.name',
                        'author' => 'author',
                        'title' => 'title',
                        'description' => 'description',
                        'url' => 'url',
                        'url_to_image' => 'urlToImage',
                        'content' => 'content',
                        'published_at' => 'publishedAt'
                    ]
                )
            ]
        );
    }
}
