<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
	    // Obtener el usuario autenticado
	    $user = Auth::user();
	    // Definir la consulta base para productos
	    $query = Product::with('category', 'file')
	        ->where('stock', '>', 0)
	        ->whereHas('category', function ($query) {
	            $query->whereNull('deleted_at');
	        })
	        ->join('categories', 'products.category_id', '=', 'categories.id')
	        ->orderBy('categories.name', 'asc') // Ordenar por nombre de la categoría
	        ->select('products.*'); // Seleccionar solo columnas de productos
	    // Si el usuario está autenticado, aplicar filtro adicional
	    if ($user) {
	        $cart_id = $user->cart->id;
	        $query->whereDoesntHave('cartProducts', function ($query) use ($cart_id) {
	            $query->where('cart_id', $cart_id);
	        });
	    }
	    // Obtener los productos
	    $products = $query->get();
		return response()->json($products);
	}

	public function get()
	{
        // Filtra productos cuyo stock sea mayor a cero y que tengan una categoría válida
    	$products = Product::with('category', 'file')
        ->where('stock', '>', 0) // Solo productos con stock mayor a cero
        ->whereHas('category', function($query) {
            $query->whereNull('deleted_at'); // Solo categorías que no están soft-deleted
        })
        ->get();
        // return view('index', compact('products'));
		return response()->json(["products" => $products], 200);
	}

	// public function search(Request $request)
    // {
    //     $query = $request->input('query');

    //     $products = Product::with('category')
    //         ->when($query, function ($queryBuilder) use ($query) {
    //             $queryBuilder->where('name', 'like', '%' . $query . '%');
    //         })
    //         ->get();

    //     return response()->json($products);
    // }

    // public function index()
	// {
    //     // Filtra productos cuyo stock sea mayor a cero y que tengan una categoría válida
    // 	$products = Product::with('category')
    //     ->where('stock', '>', 0) // Solo productos con stock mayor a cero
    //     ->whereHas('category', function($query) {
    //         $query->whereNull('deleted_at'); // Solo categorías que no están soft-deleted
    //     })
    //     ->get();
    //     return view('products.index', compact('products'));
	// }

	// public function store(ProductRequest $request)
	// {
    //     try {
    //         DB::beginTransaction();
    //         $product = new Product($request->all());
    //         $product->save();
    //         $this->uploadFile($product, $request);
    //         DB::commit();
    //         return response()->json([], 200);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         throw $th;
    //     }
	// }


	// public function show(Product $product)
	// {
    //     return response()->json(['product' => $product], 200);
	// }

	// public function update(ProductUpdateRequest $request, Product $product)
	// {
    //     try {
    //         DB::beginTransaction();
    //         $product->update($request->all());
    //         $this->uploadFile($product, $request);;
    //         DB::commit();
    //         return response()->json([], 204);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         throw $th;
    //     }
	// }

	// public function destroy(Product $product)
	// {
    //     $product->delete();
    //     $this->deleteFile($product);
    //     return response()->json([], 204);
	// }
}
