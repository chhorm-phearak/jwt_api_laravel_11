<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
{
    /**
     * Function: index
     * method: GET
     */
    public function index()
    {
        $slides = Slide::orderBy('id', 'DESC')->get();
        $data = [];
        foreach ($slides as $slide) {
            $data[] = [
                'id' => $slide->id,
                'title' => $slide->title,
                'description' => $slide->description,
                'link' => $slide->link,
                'status' => $slide->status,
                'images' => ($slide->images != null) ? asset('uploads/slide_images/' . $slide->images) : 'Empty Image',
            ];
        }
        //$slides = Slide::get();
        return response()->json([
            'success' => true,
            'message' => 'Slide data fetched successfully',
            'data' => $slides,
        ], 200);
    }

    /**
     * Function: show
     * @param id
     * method: GET
     */
    public function show($id)
    {
        $slide = Slide::find($id);
        if (!$slide) {
            return response()->json([
                'success' => false,
                'message' => '404 - Slide Not Found !',
                'data' => [],
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Slide data fetched by id successfully',
            'data' => $slide,
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
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'link' => 'required|string|max:50',
            'status' => 'required|integer',
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
            $file_slide = $request->file('images');
            $image_slide = date('d-m-y-') . rand(000, 9999999) . '.' . $file_slide->getClientOriginalExtension();
            $file_slide->move(public_path('uploads/slide_images/'), $image_slide);

            $file_path = 'uploads/slide_images/' . $image_slide;
        }
        $slide = Slide::create([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'status' => $request->status,
            'images' => $file_path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slide created successfully',
            'data' => $slide,
        ], 201);
    }

    /**
     * Function: edit
     * @param id
     * method: GET
     */
    public function edit($id)
    {
        $slide = Slide::find($id);
        if (!$slide) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Not Found !',
            ], 404);
        }

        $data_Response = [
            'id' => $slide->id,
            'title' => $slide->title,
            'description' => $slide->description,
            'link' => $slide->link,
            'status' => $slide->status,
            'images' => ($slide->images != null) ? asset('uploads/slide_images/' . $slide->images) : 'Empty Image',
        ];
        return response()->json([
            'success' => true,
            'message' => 'Slide Is Found !',
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
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'link' => 'required|string|max:50',
            'status' => 'required|integer',
            'images' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $slide = Slide::find($id);
        if (!$slide) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Slide Not Found !',
            ], 404);
        }

        $slide->title = $request->title;
        $slide->description = $request->description;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if ($request->file('images') != null) {
            $file_slide = $request->file('images');
            $image_slide = date('d-m-y') . rand(000, 9999999) . '.' . $file_slide->getClientOriginalExtension();
            $file_slide->move(public_path('uploads/slide_images'), $image_slide);

            if ($slide->images != null) {
                $file_images_Dir  = public_path('uploads/slide_images/' . $slide->images);
                if (File::exists($file_images_Dir)) {
                    File::delete($file_images_Dir);
                }
            }
        } else {
            $image_slide = $slide->images;
        }
        $slide->images = $image_slide;

        $slide->update();

        return response()->json([
            'success' => true,
            'message' => 'Slide updated successfully',
            'data' => $slide,
        ], 201);
    }

    /**
     * Function: delete
     * @param id
     * method: POST
     */
    public function delete($id)
    {
        $slide = Slide::find($id);
        if (!$slide) {
            return response()->json([
                'success' => false,
                'errors' => '404 - Not Found !',
            ], 404);
        }

        if ($slide->images != null) {
            $file_images_Dir  = public_path('uploads/slide_images/' . $slide->images);
            if (File::exists($file_images_Dir)) {
                File::delete($file_images_Dir);
            }
        }

        $slide->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slide deleted successfully',
            'data' => $slide,
        ], 200);
    }
}
