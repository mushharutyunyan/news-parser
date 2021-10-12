<?php

namespace App\Jobs;

use App\Models\ApiSource;
use App\Models\News;
use App\Models\Query;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Source;

class NewsParserJob extends Job
{
    /**
     * @var array
     */
    protected $source;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($source_id)
    {
        $this->source = ApiSource::find($source_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d');
        $fromFirst = true;

        // Get next query
        if(!$this->source->queries()->count()) {
            $query = Query::find(1);
            $this->source->queries()->attach($query);
        } else {
            if (!$query = Query::where('id', '>', $this->source->queries->last()->id)->first()) {
                $query = Query::find(1);
            }
            $this->source->queries()->attach($query);
        }

        if ($this->source->news->count()) {
            $lastNews = $this->source->news()->orderBy('published_at', 'DESC')->first();
            $date = $lastNews->published_at;
            $fromFirst = false;
        }

        $client = new Client(); // Guzzle Client
        $url = $this->source->url;
        $queryString = $this->source->query_string;
        $params = array(
            '{$query}' => $query->name,
            '{$date_from}' => $date,
            '{$key}' => env($this->source->key_prefix . '_KEY')
        );

        $response = $client->get($url . strtr($queryString, $params));
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['totalResults']) {
                $structure = json_decode($this->source->news_structure, true);
                if($fromFirst) {
                    $this->saveNews($data['articles'][0], $structure, $query);
                } else {
                    // loop while finding a last active data
                    for ($i = 1; $i <= $data['totalResults']; $i++) {
                        if ($data['totalResults'] - $i < 0) {
                            break;
                        }
                        $newsData = $data['articles'][0];
                        if(isset($data['articles'][$data['totalResults'] - $i])) {
                            $newsData = $data['articles'][$data['totalResults'] - $i];
                        }

                        // Check if exists
                        if (News::where('source_id', $this->source->id)->where('title', $newsData['title'])->where(
                            'published_at',
                            $newsData[$structure['published_at']]
                        )->count()) {
                            continue;
                        }
                        $this->saveNews($newsData, $structure, $query);
                        break;
                    }
                }
            }
        }
    }


    private function saveNews($newsData, $structure, $query) {
        $news = new News();
        $news->source_id = $this->source->id;
        $news->query_id = $query->id;
        foreach ($structure as $field => $outField) {
            $value = $this->getOutFieldValue($newsData, $outField);
            if($field == 'published_at') {
                $value = Carbon::parse($value);
            }
            $news->$field = $value;
        }
        $news->save();
    }

    private function getOutFieldValue($newsData, $field)
    {
        $parseStructure = explode('.', $field);
        if(count($parseStructure) > 1) {
            $value = $newsData;
            foreach($parseStructure as $key) {
                $value = $value[$key];
            }
            return $value;
        } else {
            return $newsData[$field];
        }
    }
}
