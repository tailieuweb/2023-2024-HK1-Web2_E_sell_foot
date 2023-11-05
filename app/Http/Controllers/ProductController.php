<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//Unknow
class ProductController extends Controller
{
    public function registrationProduct()
    {
        return view('admin.content.addproduct');
    }

    public function customProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'instock' => 'required',
            'sold' => 'required',
            'id_category' => 'required',
            'photo' => 'required',
        ]);
        $file = $request->file('photo');
        $path = 'uploads';
        $fileName = $file->getClientOriginalName();
        $file->move($path, $fileName);
        $product = new Product($request->all());
        $product->photo = $fileName;
        $product->save();
        return redirect("listproduct");
    }
    public function createProduct(array $data)
    {
        return Product::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'instock' => $data['instock'],
            'sold' => $data['sold'],
            'id_category' => $data['id_category'],
            'photo' => $data['photo'],
        ]);
    }

    public function getDataEdit($id)
    {
        $getData = DB::table('products')->select('*')->where('id', $id)->get();
        return view('admin.content.editproduct')->with('getDataProductById', $getData);
    }

    public function updateProduct(Request $request)
    {
        $file = $request->file('photo');
        $path = 'uploads';
        $fileName = $file->getClientOriginalName();
        $file->move($path, $fileName);

        $updateData = DB::table('products')->where('id', $request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'instock' => $request->instock,
            'sold' => $request->sold,
            'id_category' => $request->id_category,
            'photo' => $fileName,
        ]);
        //Thực hiện chuyển trang
        return redirect('listproduct');
    }


    public function deleteProduct($id)
    {
        $deleteData = DB::table('products')->where('id', '=', $id)->delete();
        return redirect('listproduct');
    }


    public function listProduct()
    {
        $products = DB::table('products')->paginate(4);
        return view('admin.content.listproduct', compact('products'));
    }

    public function searchProduct(Request $request)
    {
        $keyword = $request->keyword;
        $products = Product::where('name', 'LIKE', '%' . $keyword . '%')->paginate(3);
        return view('shop/shop', compact('products'));
    }
}
