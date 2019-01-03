<?php

use App\Model\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeders extends Seeder
{
    public $categories = [
        'Suporte' => ['Usuário em treinamento'],
        'Comercial' => ['Prospect'],
        'Recursos Humanos' => ['Entrevista'],
    ];

    public $a_bg_color = ['#e6b8af','#f4cccc','#fce5cd','#fff2cc','#d9ead3','#d0e0e3','#c9daf8','#cfe2f3','#d9d2e9','#ead1dc'];

    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach ($this->categories as $name => $tags) {
            $category = Category::firstOrNew(['name'  => $name]);
            $category->color = $faker->hexcolor;
            $category->save();

            foreach ($tags as $tag){
                $category->tags()->create([
                    'name' => $tag,
                    'bg_color' => $this->a_bg_color[array_rand($this->a_bg_color)],
                    'text_color' => '#0c343d',
                ]);
            }
        }
    }
}