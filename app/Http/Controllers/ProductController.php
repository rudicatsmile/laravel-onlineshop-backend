<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Product;


use Illuminate\Http\Request;

class ProductController extends Controller
{
    //index
    public function index(Request $request)
    {
        
        
       // $products = \App\Models\Product::paginate(5);
        
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name AS category_name')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('products.name', 'like', '%' . $name . '%');
            })
            ->paginate(5);
       
        return view('pages.product.index', compact('products'));
    }

    //create
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('pages.product.create', compact('categories'));
    }

    //store
    public function store(Request $request)
    {
        

        if ($request->input('image')) {
            // $data['image'] = $request->input('image');
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
        } else {
            //if image is empty, then use the old password
            $filename = '';
        }
        
        // $data = $request->all();

        $product = new \App\Models\Product;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = (int) $request->price;
        $product->stock = (int) $request->stock;
        $product->category_id = $request->category_id;
        $product->image = $filename;
        $product->save();

        return redirect()->route('product.index');
    }

    //edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('pages.product.edit', compact('product','categories'));
    }

    //update
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = Product::findOrFail($id);
        //check if Image is not empty
        // if ($request->input('image')) {
        //     $data['image'] = $request->input('image');
        // } else {
        //     //if image is empty, then use the old password
        //     $data['image'] = $product->image;
        // }
        $product->update($data);
        return redirect()->route('product.index')->with('success', 'User successfully updated');

    }

    //destroy
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'User successfully deleted');

    }
}
