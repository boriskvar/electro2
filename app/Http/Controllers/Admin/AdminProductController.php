<?php

namespace App\Http\Controllers\Admin;


use App\Models\Cart;
use App\Models\Menu;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // Вывод списка продуктов
    public function index(Request $request)
    {
        // Определяем допустимые поля для сортировки
        $allowedSortFields = ['popular', 'position', 'created_at', 'name']; // Добавьте другие поля по необходимости

        // Получаем параметры сортировки и пагинации из запроса
        $sort = $request->input('sort', 'position'); // Поле для сортировки, по умолчанию 'position'
        $order = $request->input('order', 'asc'); // Порядок сортировки, по умолчанию 'asc'по возрастанию(ОТ МЕНЬШЕГО к большему),desc — по убыванию (от большего к меньшему)
        $perPage = $request->input('per_page', 25); // Количество элементов на страницу, по умолчанию 25
        $page = $request->input('page', 1); // Текущая страница

        // Проверяем, является ли поле сортировки допустимым
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'position'; // По умолчанию сортируем по 'position' если поле недопустимо
        }

        // Выполняем запрос к базе данных с учетом отзывов и оценок
        $products = Product::withCount('reviews') // Подсчитываем количество отзывов
            ->withAvg('reviews', 'rating') // Вычисляем среднюю оценку
            ->when($sort === 'popular', function ($query) use ($order) {
                // Сортировка по популярности (количество отзывов и средняя оценка)
                return $query->orderBy('reviews_count', $order)
                    ->orderBy('reviews_avg_rating', $order);
            }, function ($query) use ($sort, $order) {
                // Сортировка по другим полям
                return $query->orderBy($sort, $order);
            })
            ->paginate($perPage, ['*'], 'page', $page);

        // Возвращаем представление с переданными данными
        return view('admin.products.index', compact('products'));
    }

    // Показать ФОРМУ (для создания нового продукта)
    public function create()
    {
        $brands = class_exists('App\Models\Brand') ? Brand::all() : collect();
        $categories = Category::all(); // Получаем все категории
        $menus = Menu::all(); // Список меню

        return view('admin.products.create', compact('categories', 'brands', 'menus')); // Передаем переменную в представление
    }

    public function store(Request $request)
    {
        // dd($request);
        // dd($request->toArray());
        // Преобразуем строки в массивы для 'colors' и 'sizes', если они строки
        if ($request->has('colors') && is_string($request->input('colors'))) {
            $request->merge([
                'colors' => explode(',', $request->input('colors')) // Преобразуем строку в массив
            ]);
        }
    
        if ($request->has('sizes') && is_string($request->input('sizes'))) {
            $request->merge([
                'sizes' => explode(',', $request->input('sizes')) // Преобразуем строку в массив
            ]);
        }
    
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'in_stock' => 'required|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'views_count' => 'nullable|integer|min:0',
            'colors' => 'nullable|array', // Проверка, что это массив
            'colors.*' => 'string', // Каждый элемент массива должен быть строкой
            'sizes' => 'nullable|array', // Проверка, что это массив
            'sizes.*' => 'string', // Каждый элемент массива должен быть строкой
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'menu_id' => 'nullable|exists:menus,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_top_selling' => 'required|boolean',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_new' => 'required|boolean',
            'position' => 'nullable|integer|min:0',
        ]);
    // dd($request->toArray());
        // Собираем данные
        $data = $request->all();
    // dd($data);
        // Рассчитываем discount_percentage
        $data['discount_percentage'] = isset($data['old_price']) && $data['old_price'] > 0
            ? round((($data['old_price'] - $data['price']) / $data['old_price']) * 100)
            : 0;
    
        // Устанавливаем значения по умолчанию для reviews_count и views_count
        $data['reviews_count'] = $data['reviews_count'] ?? 0;
        $data['views_count'] = $data['views_count'] ?? 0;
    
        // Обработка загрузки изображений
        if ($request->hasFile('images')) {
            $imagePaths = []; // Массив для хранения путей новых изображений
            foreach ($request->file('images') as $image) {
                // Генерация уникального имени для каждого изображения
                $filename = $image->getClientOriginalName();// или можно использовать `uniqid()` для уникальности
                $path = $image->storeAs('img', $filename, 'public'); // Сохраняем изображение в папку 'public/img'
                $imagePaths[] = basename($path); // Сохраняем только имя файла (без пути)
            }
             // Сохраняем массив изображений (Laravel автоматически конвертирует в JSON при сохранении в базе данных)
            $data['images'] = $imagePaths;
        } else {
            // Если изображения нет, сохраняем пустой массив
            $data['images'] = [];
        }
    // dd($data);
        // Создаем продукт
        Product::create($data);

        // Редирект на список продуктов с сообщением об успехе
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    // Показать один продукт
    public function show(Product $product)
    {
        // dd($product->toArray());
        // dd($product->images); //[ 0 => "product01.png"]-раз уже хранится в массиве то декодирование не нужно 
        // Временно используем временный ID пользователя для тестирования
        $userId = 1; // Замените на Auth::id() для продакшн-версии

        // Получаем количество товара в корзине для текущего пользователя
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $product->id)->first();
        $currentQuantity = $cartItem ? $cartItem->stock_quantity : 0; // Если товар в корзине, получаем его количество, иначе 0

            // Получаем все заказы пользователя или пустой набор
    $orders = Order::where('user_id', $userId)->get();
// dd($orders);
    // Если заказов нет, можно вернуть пустое представление или сообщение
    if ($orders->isEmpty()) {
        // В случае, если заказов нет, можете отобразить сообщение или пустую страницу
        return view('admin.products.show', compact('product', 'currentQuantity', 'orders'));
    }
//    dd($orders); 
        // Передаем продукт и текущее количество в представление
        return view('admin.products.show', compact('product', 'currentQuantity', 'orders'));
    }



    // Показать форму для редактирования продукта
    public function edit(Product $product)
    {
        // dd($product->toArray());
        $categories = Category::all(); // Получаем все категории
        $brands = Brand::all(); // Получаем все бренды (если нужно)
        $menus = Menu::all();  // Добавляем список меню
        
        // На этом этапе images уже будет массивом, благодаря кастингу
        $images = $product->images;
        // dd($images); //[ 0 => "product01.png" ]

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'menus'));
    }

    // Обновить существующий продукт
    public function update(Request $request, Product $product)
    {
        // Валидация данных запроса
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'price' => 'nullable|numeric',
            'old_price' => 'nullable|numeric',
            'in_stock' => 'required|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'views_count' => 'nullable|integer|min:0',
            'colors' => 'nullable|array',
            'colors.*' => 'required|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock_quantity' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'menu_id' => 'nullable|exists:menus,id',  // Аналогично полю brand_id
            'is_top_selling' => 'required|boolean',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_new' => 'required|boolean',
            'position' => 'nullable|integer|min:0',
        ]);
    
        // Собираем данные из запроса
        $data = $request->all();
    
        // Проверяем загрузку файлов
        if ($request->hasFile('images')) {
            // Удаление старых изображений
            if (!empty($product->images)) {
                foreach ($product->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
    
            // Загрузка новых изображений
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                try {
                    // Получаем оригинальное имя файла
                    $filename = $image->getClientOriginalName();
    
                    // Сохраняем файл с оригинальным именем в папку 'public'
                    $path = $image->storeAs('', $filename, 'public'); // Указываем 'products' как папку для сохранения
    
                    // Добавляем путь к изображению в массив
                    $imagePaths[] = $filename;
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['images' => 'Ошибка при загрузке изображения: ' . $e->getMessage()]);
                }
            }
    
            // Сохраняем пути к изображениям в массив данных
            $data['images'] = $imagePaths;
        } else {
            // Если новые изображения не загружены, оставляем старые
            $data['images'] = $product->images;
        }
    
        // Обновляем продукт
        $product->update($data);
    
        return redirect()->route('admin.products.index')->with('success', 'Продукт обновлен успешно');
    }
    
    

    // Удалить продукт
    public function destroy(Product $product)
    {
        // dd($product->toArray()); // ["images" => "["product07.png"]"]
        // Удаляем связанные изображения, если они есть
        if ($product->images) {
            $imagePaths = $product->images; // Декодируем пути к изображениям
            foreach ($imagePaths as $imagePath) {
                // Удаляем файл из файловой системы
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Удаляем продукт
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function search(Request $request)
    {
        //dd($request->toArray()); // []
        $query = $request->input('query');
        // Если запрос пустой, можно вернуть пустую коллекцию или сообщение
        if (!$query) {
            return view('admin.products.search', ['products' => collect(), 'query' => '']);
        }

        //dd($query); //null
        // Выполняем поиск по названию продукта
        $products = Product::where('name', 'LIKE', '%' . $query . '%')->get();
        //dd($products->toArray()); // [ 0 => array:23 [▶]  1 => array:23 [▶] ]
        return view('admin.products.search', compact('products', 'query'));
    }
}