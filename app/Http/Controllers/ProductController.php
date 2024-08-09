<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    use UploadFile;

	public function home()
	{
		return view('index');
	}

	public function index()
	{
		return view('products.index');
	}

	public function getAll()
	{
	    $user = Auth::user();
	    $selectedProducts = collect();
	    $categories = Category::whereNull('deleted_at')->orderBy('name', 'asc')->get();
	    foreach ($categories as $category) {
	        $query = Product::with('category', 'file')
	            ->where('stock', '>', 0)
	            ->where('category_id', $category->id)
	            ->inRandomOrder()
	            ->limit(4);

	        if ($user) {
	            $cart_id = $user->cart->id;
	            $query->whereDoesntHave('cartProducts', function ($query) use ($cart_id) {
	                $query->where('cart_id', $cart_id);
	            });
	        }

	        $selectedProducts = $selectedProducts->merge($query->get());
	    }
	    return response()->json($selectedProducts);
	}

	public function get()
	{
    	$products = Product::with('category', 'file')
        ->where('stock', '>', 0)
        ->whereHas('category', function($query) {
            $query->whereNull('deleted_at');
        })
        ->get();
		return response()->json(["products" => $products], 200);
	}

	public function store(ProductRequest $request)
	{
		try {
	        DB::beginTransaction();
	        $product = new Product($request->all());
	        $product->save();
	        $this->uploadFile($product, $request);
	        DB::commit();
			return response()->json([], 200);
	    } catch (\Throwable $th) {
	        DB::rollBack();
			throw $th;
	    }
	}

    public function getAllDt()
    {
        $products = Product::with('file', 'category')->get();
        return DataTables::of($products)->toJson();
    }


	public function show(Product $product)
	{
        return response()->json(['product' => $product->load('category', 'file')], 200);
	}

	public function update(Product $product, ProductUpdateRequest $request)
	{
		try {
            DB::beginTransaction();
            $product->update($request->all());
            $this->uploadFile($product, $request);
            DB::commit();
            return response()->json([], 204);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
	}

	public function destroy(Product $product)
	{
        $product->delete();
        $this->deleteFile($product);
        return response()->json([], 204);
	}
}
