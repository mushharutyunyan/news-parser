<?php

namespace Database\Seeders;

use App\Models\Query;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QueriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Bitcoin',
                'slug' => Str::slug('Bitcoin')
            ],
            [
                'name' => 'Litecoin',
                'slug' => Str::slug('Litecoin')
            ],
            [
                'name' => 'Ripple',
                'slug' => Str::slug('Ripple')
            ],
            [
                'name' => 'Dash',
                'slug' => Str::slug('Dash')
            ],
            [
                'name' => 'Ethereum',
                'slug' => Str::slug('Ethereum')
            ],
        ];
        foreach ($data as $value) {
            if (Query::where('slug',$value['slug'])->count()) {
                continue;
            }
            $query = new Query();
            $query->slug = $value['slug'];
            $query->name = $value['name'];
            $query->save();
        }
    }
}
