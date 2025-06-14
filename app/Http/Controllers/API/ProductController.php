<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Function: index
     * method: GET
     */
    public function index()
    {
        $products = Product::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])->orderBy('id', 'DESC')->get();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'images' => ($product->images != null) ? asset('uploads/product_images/' . $product->images) : 'Empty Image',
            ];
        }
        return response()->json([
            'success' => true,
            'message' => 'Products data fetched successfully',
            'data' => $products,
        ], 200);
    }

    /**
     * Function: show
     * @param id
     * method: GET
     */
    public function show($id)
    {
        $product = Product::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])->find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => '404 - Product Not Found !',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product data fetched by id successfully',
            'data' => $product,
        ], 200);
    }

    /**
     * Function: store
     * @param request
     * method: POST
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'price' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = new Product();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        if ($request->file('images') != null) {
            $file_product = $request->file('images');
            $image_product = date('d-m-y') . rand(000, 9999999) . '.' . $file_product->getClientOriginalExtension();
            $file_product->move(public_path('uploads/product_images'), $image_product);
            $product->images = $image_product;
        }
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Function: edit
     * @param id
     * method: GET
     */
    public function edit($id)
    {

        $product = Product::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Product Not Found !',
            ], 404);
        }

        $data_Response = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'category_id' => $product->category_id,
            'images' => ($product->images != null) ? asset('uploads/product_images/' . $product->images) : 'Empty Image',
        ];
        return response()->json([
            'success' => true,
            'message' => 'Product Is Found !',
            'data' => $data_Response,
        ], 200);
    }

    /**
     * Function: update
     * @param request
     * @param id
     * method: POST
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'price' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Product Not Found !',
            ], 404);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        if ($request->file('images') != null) {
            $file_product = $request->file('images');
            $image_product = date('d-m-y') . rand(000, 9999999) . '.' . $file_product->getClientOriginalExtension();
            $file_product->move(public_path('uploads/product_images'), $image_product);

            if ($product->images != null) {
                $file_images_Dir  = public_path('uploads/product_images/' . $product->images);
                if (File::exists($file_images_Dir)) {
                    File::delete($file_images_Dir);
                }
            }
        } else {
            $image_product = $product->images;
        }
        $product->images = $image_product;

        $product->update();

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Function: delete
     * @param id
     * method: POST
     */
    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Product Not Found !',
            ], 404);
        }

        if ($product->images != null) {
            $file_images_Dir  = public_path('uploads/product_images/' . $product->images);
            if (File::exists($file_images_Dir)) {
                File::delete($file_images_Dir);
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
            'data' => $product,
        ], 200);
    }
}
