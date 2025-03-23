<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product; // модель товара
// use Illuminate\Support\Facades\Auth;

class UserCartController extends Controller
{
    // Показать содержимое корзины
    /* public function index()
    {
        //$userId = Auth::id(); // Получаем ID текущего пользователя
        // $userId = Auth::id() ?? 1; // Используем Auth::id() или временный ID для тестирования
        $userId = 1;
        // Получаем корзину для текущего пользователя из сессии
        $cart = session()->get('cart_' . $userId, []);

                // Если корзина пуста, можно обработать это (например, показывать уведомление)
                if (empty($cart)) {
                    return view('user.cart.index', [
                        'cartItems' => [],
                        'totalPrice' => 0,
                        'message' => 'Ваша корзина пуста',
                        'contacts' => [], // Передаем пустой массив для контактов
                        'breadcrumbs' => [], // Передаем пустой массив для хлебных крошек
                    ]);
                }
                
        // Вычисляем общую стоимость
        $totalPrice = array_sum(array_column($cart, 'total'));
        
        // Получаем данные контактов пользователя с ID = 1
        $user = User::find($userId); // Ищем пользователя с данным ID
        $contacts = $user ? $user->contacts : []; // Получаем контакты, если пользователь найден

            // Пример хлебных крошек (можно настроить по своему)
        $breadcrumbs = [
            ['name' => 'Главная', 'url' => route('home')],
            ['name' => 'Корзина', 'url' => route('user.cart.index')]
        ];
dd($breadcrumbs);
        return view('user.cart.index', [
            'cartItems' => $cart,
            'totalPrice' => $totalPrice,
            'contacts' => $contacts, // Передаем данные о контактах
            'breadcrumbs' => $breadcrumbs, // Передаем хлебные крошки
        ]);
    } */

    // Добавить товар в корзину
    /* public function add($productId)
    {
        // $userId = Auth::id() ?? 1;
        $userId = 1;
        $product = Product::find($productId);
        
        if (!$product) {
            return redirect()->route('user.cart.index')->with('error', 'Товар не найден.');
        }

        // Проверка наличия на складе
        if ($product->in_stock <= 0) {
            return redirect()->route('user.cart.index')->with('error', 'Товар недоступен на складе.');
        }
        


        $price = $product->price;
        $discount = ($price * ($product->discount_percentage ?? 0)) / 100;
        $total = $price - $discount;

        $image = $this->getProductImage($product);
        $cart = session()->get('cart_' . $userId, []);

        // Если товар уже в корзине, увеличиваем количество
        if (isset($cart[$productId])) {
            // Если количество товара в корзине меньше чем на складе
            if ($cart[$productId]['qty'] < $product->in_stock) {
                // Увеличиваем количество товара
                $cart[$productId]['qty']++;
                // Перерасчитываем общую стоимость
                $cart[$productId]['total'] += $total;
            } else {
                return redirect()->route('user.cart.index')->with('error', 'Недостаточно товара на складе.');
            }
        } else {
            // Если товара нет в корзине, добавляем его
            $cart[$productId] = [
                'productId' => $product->id,
                'name' => $product->name, // Добавляем имя товара
                'image' => $image,  // Убедитесь, что обрабатывается JSON-строка
                'qty'  => 1,  // Начальное количество товара
                'price' => $price,
                'discount' => $discount,
                'total' => $total
            ];
        }
    
        // Сохраняем корзину в сессии
        session()->put('cart_' . $userId, $cart);

        // Перенаправляем с сообщением об успешном добавлении товара в корзину
        return redirect()->route('user.cart.index')->with('success', 'Товар добавлен в корзину.');
    } */


    
    // Обновить количество товара в корзине
    /* public function update($productId, Request $request)
    {
        //dd($request->all()); //"quantity" => "3"

        // Получаем ID текущего пользователя
        // $userId = Auth::id() ?? 1;
        $userId = 1;
        $cart = session()->get('cart_' . $userId, []);

        if (!isset($cart[$productId])) {
            return redirect()->route('user.cart.index')->with('error', 'Товар не найден в корзине.');
        }

        // Валидация входящих данных
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        //dd($validatedData); // Проверяем валидированные данные ["quantity" => "2"]

        // Обновляем количество и общую сумму
        $cart[$productId]['qty'] = $validatedData['quantity'];
        $cart[$productId]['total'] = $cart[$productId]['price'] * $validatedData['quantity'];

        //dd($cartItem->toArray()); //"quantity" => "2"


        session()->put('cart_' . $userId, $cart);

        // Перенаправляем на страницу корзины с сообщением об успешном обновлении;
        return redirect()->route('user.cart.index')->with('success', 'Количество товара обновлено');
    } */

    // Удалить товар из корзины
    public function remove($productId)
    {
        // $userId = Auth::id() ?? 1;
        $userId = 1;
        $cart = session()->get('cart_' . $userId, []);

        if (!isset($cart[$productId])) {
            return redirect()->route('user.cart.index')->with('error', 'Товар не найден в корзине.');
        }
        unset($cart[$productId]);
        session()->put('cart_' . $userId, $cart);

        // Перенаправляем на страницу корзины с сообщением об успешном удалении
        return redirect()->route('user.cart.index')->with('success', 'Товар удален из корзины');
    }

        // Метод для получения изображения товара
        private function getProductImage($product)
        {
            if (!empty($product->images)) {
                $decodedImages = json_decode($product->images, true);
                return is_array($decodedImages) ? $decodedImages[0] ?? 'default.jpg' : 'default.jpg';
            }
            return 'default.jpg';
        }
}
