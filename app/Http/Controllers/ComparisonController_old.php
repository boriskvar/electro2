<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comparison;
use App\Models\Product;

class ComparisonController extends Controller
{
    public function index()
    {
        $comparison = Comparison::with('products')->first();
        return view('web.comparison.index', compact('comparison'));
    }

    public function add(Request $request)
    {
        $comparison = Comparison::firstOrCreate(['user_id' => auth()->id()]);
        $product = Product::find($request->product_id);

        if ($product && !$comparison->products->contains($product->id)) {
            $comparison->products()->attach($product->id);
        }

        return redirect()->route('web.comparison.index');
    }

    public function remove(Request $request)
    {
        $comparison = Comparison::where('user_id', auth()->id())->first();
        if ($comparison) {
            $comparison->products()->detach($request->product_id);
        }

        return redirect()->route('web.comparison.index');
    }

    public function clear()
    {
        $comparison = Comparison::where('user_id', auth()->id())->first();
        if ($comparison) {
            $comparison->products()->detach();
            $comparison->delete();
        }

        return redirect()->route('web.comparison.index');
    }
}
