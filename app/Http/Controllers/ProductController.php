<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the product.
     */
    public function index()
    {
        $products = Product::where('active',true)
            ->orderBy('name')
            ->paginate(10);

        return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('active', true)
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price_in_cents' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
        ->with('success','Produto cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('active', true)
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price_in_cents' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
        ->with('success','Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->update(['active' => false]);

        return redirect()->route('products.index')
        ->with('sucess','Produto desativado com sucesso.');
    }
}
