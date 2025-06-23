<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Function: index
     */
    public function index()
    {
        $brands = Brand::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])->orderBy('id', 'DESC')->get();
        return response()->json([
            'success' => true,
            'message' => 'Brand data fetched successfully',
            'data' => $brands,
        ], 200);
    }
    /**
     * Function: show
     * @param id
     */
    public function show($id)
    {
        $brand = Brand::with('category')->find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => '404 - Brand Is Not Found !',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Brand Is Found !',
            'data' => $brand,
        ], 200);
    }

    /**
     * Function: store
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'code' => 'required|string|max:20',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->code = $request->code;
        $brand->category_id = $request->category_id;
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => $brand,
        ], 201);
    }
    /**
     * Function: edit
     * @param id
     */
    /**
     * Function: update
     */
    /**
     * Function: delete
     * @param id
     */
    public function delete($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Brand Is Not Found !',
            ], 404);
        }
        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully',
            'data' => $brand,
        ], 200);
    }
}
