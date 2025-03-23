<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Contact;
use App\Models\Brand;

class StoreController extends Controller
{
    public function index()
    {       
        // Получаем данные из базы
        // $contacts = Contact::first(); // Получаем первый контакт или null
        $contacts = Cache::remember('contacts_first', 3600, function () {
            return Contact::first();
        });
        
        // dd($contacts);
        
        $breadcrumbs = [
            ['name' => 'Store', 'url' => route('store')]
        ];
        // $pageTitle = 'Home Page';

        // @dd($breadcrumbs);
        
        // Получаем все категории
        // $categories = Category::all();
        // Загружаем категории с продуктами
        $categories = Category::with('products')->get();

        // Получаем все продукты
        // $products = Product::all();
        // $brands = Brand::all();
        // Выбираем только необходимые данные для продуктов и брендов
        $products = Product::select('id', 'name', 'price', 'category_id', 'brand_id')->get();
        $brands = Brand::select('id', 'name')->get();
        
        return view('web.store', compact('contacts' ,'categories', 'products', 'breadcrumbs', 'brands'));
        // return view('home', compact(  'categories', 'products', 'breadcrumbs'));
    }

    public function contact()
    {
        // Получаем данные о контакте, например, первый контакт
        $contact = Contact::first(); 

        // Возвращаем представление с данными контакта
        return view('pages.contact', compact('contact'));
    }
}