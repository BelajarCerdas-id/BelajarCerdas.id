<?php 

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{

    use HasFactory;

    protected $fillable = ['title', 'author', 'slug', 'body'];
    // public static function all() 
    // {
    //     return [
    //     [
    //         'id' => 1,
    //         'slug' => 'judul-artikel-1',
    //         'title' => 'Judul Artikel 1',
    //         'author' => 'Dafa Marchiano',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed fuga, doloribus rerum vel nesciunt labore est sunt
    //         tenetur cum placeat quaerat reprehenderit consectetur minus eligendi assumenda nostrum facilis id iure?'
    //     ],
    //     [
    //         'id' => 2,
    //         'slug' => 'judul-artikel-2',
    //         'title' => 'Judul Artikel 2',
    //         'author' => 'Dafa Marchiano',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed fuga, doloribus rerum vel nesciunt labore est sunt
    //         tenetur cum placeat quaerat reprehenderit consectetur minus eligendi assumenda nostrum facilis id iure?'
    //     ]
    //     ];
    // }

    // public static function find($slug): Array //untuk memberikan spesifik kesalahhan
    // {
    // //     return Arr::first(static::all(), function ($post) use ($slug) {
    // //     return $post['slug'] == $slug;
    // // }); fungsi callback

    //     $post = Arr::first(static::all(), fn($post) => $post['slug'] == $slug); //arrow function
        
    //     if(! $post) {
    //         abort(404);
    //     }

    //     return $post;
    // }
}