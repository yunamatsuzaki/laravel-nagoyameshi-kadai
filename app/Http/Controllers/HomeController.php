<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // $highly_rated_restaurantsはレビューの高い順に並べ替え
        $highly_rated_restaurants = Restaurant::withAvg('reviews', 'score')->orderBy('reviews_avg_score', 'desc')->take(6)->get();
        $categories = Category::all(); 
        // $new_restaurantsから最新の6件を取得する
        $new_restaurants = Restaurant::orderBy('id', 'desc')->take(6)->get();

        return view('/home', compact('highly_rated_restaurants', 'categories', 'new_restaurants'));
    }
}