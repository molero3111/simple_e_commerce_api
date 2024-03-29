<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use \Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $request->validate([
            'name' => 'required', 'slug' => 'required',
            'price' => 'required', 'stock' => 'required',
        ]);

        if ($request->user()->cannot('create', Product::class)) {
            return ['type' => 'error', 'message' => 'You are not authorized to create new products.'];
        }

        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Product::find($product->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->user()->cannot('update', $product)) {
            return ['type' => 'error', 'message' => 'You are not authorized to update products.'];
        }
        return Product::find($product->id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {
        if ($request->user()->cannot('delete', $product)) {
            return ['type' => 'error', 'message' => 'You are not authorized to delete products.'];
        }
        return Product::destroy($product->id);
    }

    /**
     * searches the specified resources from storage.
     *
     * @param  str $name
     * @return \Illuminate\Http\Response
     */
    public function search(String $name)
    {
        return Product::where('name', 'like', "%" . $name . "%")->get();
    }
}
