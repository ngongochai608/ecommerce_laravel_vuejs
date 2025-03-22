<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\HistoryWarehouse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->get();
        } else {
            $products = Product::orderBy('quantity', 'asc')->get();
        }
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'priority' => 'nullable|integer',
            'quantity' => 'required|integer',
            'status' => 'nullable|string',
            'price' => 'required|numeric',
            'profit' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $image_file_extension = $image_file->getClientOriginalExtension();
            $fileName = Str::slug($data['name']).'-'.now()->timestamp.'.'.$image_file_extension;
            $pathFolder = 'uploads/product/'.date('d-m-Y');
            $image_file->move(public_path($pathFolder), $fileName);
            $filePath = asset($pathFolder . '/' . $fileName); 
            $data['image'] = $filePath;
        }

        return Product::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('image')) {
            $request->validate([
                'name' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'priority' => 'nullable|integer',
                'status' => 'nullable|string',
                'price' => 'required|numeric',
                'profit' => 'required|numeric',
                'category_id' => 'required|integer|exists:categories,id',
            ]);

            if ($category->image) {
                $oldImagePath = public_path(str_replace(asset('/'), '', $category->image));
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $data = $request->except(['image']);

            $image_file = $request->file('image');
            $image_file_extension = $image_file->getClientOriginalExtension();
            $fileName = Str::slug($data['name']).'-'.now()->timestamp.'.'.$image_file_extension;
            $pathFolder = 'uploads/product/'.date('d-m-Y');
            $image_file->move(public_path($pathFolder), $fileName);
            $filePath = asset($pathFolder . '/' . $fileName); 
            $data['image'] = $filePath;
            $product->update($data);
        } else {
            // $request->validate([
            //     'name' => 'required|string',
            //     'priority' => 'nullable|integer',
            //     'status' => 'nullable|string',
            //     'price' => 'required|numeric',
            //     'profit' => 'required|numeric',
            //     'category_id' => 'required|integer|exists:categories,id',
            //     'image' => 'nullable|string',
            // ]);
            $product->update($request->all());
        }
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            $imagePath = public_path(str_replace(asset('/'), '', $product->image));
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $product->delete();
        return response()->noContent();
    }

    public function addHistoryWarehouse (Request $request) {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
        ]);
        HistoryWarehouse::create($request->all());
    }

    public function getHistoryWarehouse () {
        return HistoryWarehouse::latest()->get();
    }
}
