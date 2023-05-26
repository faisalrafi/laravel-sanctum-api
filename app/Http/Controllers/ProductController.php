<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => 'required',
            'price' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try{
            // Store the uploaded image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            // Get the full image URL
            $imageUrl = url('/images/' . $imageName);

            Product::create([
                'name'         => $request->name,
                'slug'         => $request->name,
                'description'  => $request->description,
                'price'        => $request->price,
                'image'        => $imageUrl,
            ]);

            

            return response()->json([
                'message' => "Successful!"
            ],201);
        }
        catch(\Exception $e){
                return response()->json([
                    'message' => "Something went really wrong!"
                ],500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Product::destroy($id);
    }

    public function search($name)
    {
        return Product::where('name', 'like', '%'.$name.'%')->get();
    }
}
