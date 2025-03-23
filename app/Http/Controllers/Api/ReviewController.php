<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        // Получаем все отзывы с их соответствующими продуктами
        $reviews = Review::with('product')->get(); // Загрузка связанных продуктов

        // Возвращаем представление с отзывами
        return view('reviews.index', compact('reviews'));
    }
    public function create()
    {
        $products = Product::all(); // Получаем список продуктов для выбора
        return view('reviews.create', compact('products'));
    }

    // Метод для добавления нового отзыва
    public function store(Request $request)
    {
        //dd($request->all());

        $request->validate([
            'product_id' => 'required|exists:products,id', // Убедитесь, что продукт существует
            'author_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|between:1,5', // Оценка от 1 до 5
            'review_text' => 'nullable|string',
        ]);

        // Создаем новый отзыв
        Review::create([
            'product_id' => $request->product_id, // Получаем product_id из запроса
            //'user_id' => auth()->id(), // Если пользователь авторизован
            'user_id' => 1, // Временно устанавливаем user_id = 1 для теста
            'author_name' => $request->author_name,
            'email' => $request->email,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return redirect()->route('products.show', $request->product_id)->with('success', 'Отзыв успешно добавлен!');
    }

    // Метод для редактирования отзыва
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    // Метод для обновления отзыва
    public function update(Request $request, $id)
    {
        //dd($request->toArray());
        //dd($id); //3
        $review = Review::findOrFail($id);
        //dd($review->toArray());
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review_text' => 'nullable|string',
        ]);
        //dd($request->toArray());
        // Обновляем отзыв
        $review->update([
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);
        //dd($review->toArray());

        //return redirect()->route('products.show', $review->product_id)->with('success', 'Отзыв успешно обновлен!');
        // Перенаправляем обратно с сообщением об успешном обновлении
        return redirect()->route('reviews.index', $review['id'])->with('success', 'Отзыв успешно обновлен!');
    }

    // Метод для удаления отзыва
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Отзыв успешно удален!');
    }
}