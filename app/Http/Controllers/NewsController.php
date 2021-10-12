<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $rules = [];
        if($request->exists('sort')) {
            $rules['sort'] = 'required|string|in:desc,asc';
        }
        if($request->exists('sort_by')) {
            $rules['sort_by'] = 'required|string|min:2|max:25';
        }
        if($request->exists('title')) {
            $rules['title'] = 'required|string|min:2|max:25';
        }
        if($request->exists('source')) {
            $rules['source'] = 'required|string|min:3|max:25';
        }
        if($request->exists('date_start')) {
            $rules['date_start'] = 'required|date|date_format:Y-m-d';
            if($request->exists('date_end')) {
                $rules['date_end'] = 'required|date|date_format:Y-m-d|after:date_start';
            }
        } else {
            if($request->exists('date_start')) {
                $rules['date_end'] = 'required|date|date_format:Y-m-d';
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data = $request->all();
        $news = new News();

        // FILTER START
        if($request->exists('source')) {
            $news = $news->whereHas('source',function($query) use($data) {
                $query->where('name','LIKE','%'.$data['source'].'%');
            });
        }
        if($request->exists('title')) {
            $news = $news->where('title','LIKE','%'.$data['title'].'%');
        }
        if($request->exists('date_start')) {
            $news = $news->where('published_at','>=',$data['date_start']." 00:00:01");
        }
        if($request->exists('date_end')) {
            $news = $news->where('published_at','<=',$data['date_end']." 23:59:59");
        }
        // FILTER END


        $sortBy = 'published_at';
        $sort = 'desc';
        if($request->exists('sort_by')) {
            $sortBy = $data['sort_by'];
        }
        if($request->exists('sort')) {
            $sort = $data['sort'];
        }
        $news = $news->orderBy($sortBy,$sort)->with('queryRow','source')->paginate(20);
        return response()->json($news);
    }
}
