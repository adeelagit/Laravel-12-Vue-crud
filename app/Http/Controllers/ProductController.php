<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(2);
        return Inertia::render('products/Index',[
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('products/Create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('message', 'Product added successfully');
    }

    public function edit(Product $product)
    {
        return Inertia::render('products/Edit',[
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product){
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('message', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Product deleted successfully');
    }

}
