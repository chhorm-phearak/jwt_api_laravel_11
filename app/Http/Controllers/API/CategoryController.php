<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Function: index
     * method: GET
     */
    public function index()
    {
        // $categories = Category::orderBy('id', 'DESC')->get();
        // $data = [];
        // foreach ($categories as $category) {
        //     $data[] = [
        //         'id' => $category->id,
        //         'name' => $category->name,
        //         'code' => $category->code,
        //         'description' => $category->description,
        //         'images' => ($category->images != null) ? asset('uploads/category_images/' . $category->images) : 'Empty Image',
        //     ];
        // }
        $categories = Category::get();
        return response()->json([
            'success' => true,
            'message' => 'Categories data fetched successfully',
            'data' => $categories,
        ], 200);
    }

    /**
     * Function: show
     * @param id
     * method: GET
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => '404 - Category Not Found !',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category data fetched by id successfully',
            'data' => $category,
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
            'code' => 'required|string|max:20',
            'description' => 'required|string|max:100',
            'images' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $file_path = "";

        if ($request->file('images') != null) {
            $file_category = $request->file('images');
            $image_category = date('d-m-y-') . rand(000, 9999999) . '.' . $file_category->getClientOriginalExtension();
            $file_category->move(public_path('uploads/category_images/'), $image_category);

            $file_path = 'uploads/category_images/' . $image_category;
        }
        $category = Category::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'images' => $file_path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Function: edit
     * @param id
     * method: GET
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Category Not Found !',
            ], 404);
        }

        $data_Response = [
            'id' => $category->id,
            'name' => $category->name,
            'code' => $category->code,
            'description' => $category->description,
            'images' => ($category->images != null) ? asset('uploads/category_images/' . $category->images) : 'Empty Image',
        ];
        return response()->json([
            'success' => true,
            'message' => 'Category Is Found !',
            'data' => $data_Response,
        ], 200);
    }

    /**
     * Function: update
     * @param request
     * @param string id
     * method: POST
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:50',
            'description' => 'required|string|max:50',
            'images' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Category Not Found !',
            ], 404);
        }

        $category->name = $request->name;
        $category->code = $request->code;
        $category->description = $request->description;

        if ($request->file('images') != null) {
            $file_category = $request->file('images');
            $image_category = date('d-m-y') . rand(000, 9999999) . '.' . $file_category->getClientOriginalExtension();
            $file_category->move(public_path('uploads/category_images'), $image_category);

            if ($category->images != null) {
                $file_images_Dir  = public_path('uploads/category_images/' . $category->images);
                if (File::exists($file_images_Dir)) {
                    File::delete($file_images_Dir);
                }
            }
        } else {
            $image_category = $category->images;
        }
        $category->images = $image_category;

        $category->update();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Function: delete
     * @param id
     * method: POST
     */
    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Category Not Found !',
            ], 404);
        }

        if ($category->images != null) {
            $file_images_Dir  = public_path('uploads/category_images/' . $category->images);
            if (File::exists($file_images_Dir)) {
                File::delete($file_images_Dir);
            }
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
            'data' => $category,
        ], 200);
    }

    /**
     * Function: search
     * @param request
     * method: POST
     */
    public function search(Request $request)
    {
        $query = Category::query();
        if ($request->has('search')) {
            $category = $request->search;
            $query->where('name', 'LIKE', "%{$category}%");
            //->orWhere('description', 'LIKE', "%{$category}%")
            //->orWhere('code', 'LIKE', "%{$category}%");
        }
        $searchCategory = $query->get();
        if (!$searchCategory) {
            return response()->json([
                'success' => false,
                'message' => '404 - Category Is Not Found !',
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Search categories successfully',
                'data' => $searchCategory,
            ], 200);
        }
    }
}
