<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    //index
    public function index()
    {
        $categories = \App\Models\Category::paginate(5);
        return view('pages.category.index', compact('categories'));
    }

     //create
     public function create()
     {
        //  $categories = \App\Models\Category::all();
        //  return view('pages.category.create', compact('categories'));
         return view('pages.category.create');
     }
 
     public function store(Request $request)
     {      
         $data = $request->all();
         Category::create($data);
         return redirect()->route('category.index')->with('success', 'Category successfully created');
     }

     //edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $category = Category::findOrFail($id);
       
        $category->update($data);
        return redirect()->route('category.index')->with('success', 'Category successfully updated');

    }

    //destroy
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category successfully deleted');

    }
}
