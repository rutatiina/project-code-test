<?php

namespace ProjectCode\User\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ProjectCode\User\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * fetch records.
     * GET - api/categories
     */
    public function index()
    {
        $records = User::get();
        $response = [
            "status" => "success",
            "data" => $records,
        ];
        return response()->json($response);
    }

    /**
     * store record.
     * POST - api/categories
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'color' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $response = [
                "status" => "error",
                "data" => $validator->errors(),
            ];
            return response()->json($response);
        }

        $record = new User();
        $record->name = $request["name"];
        $record->slug = Str::of($request["name"])->slug('-');
        $record->description = $request["description"];
        $record->save();
        $record = $record->fresh();

        $response = [
            "status" => "success",
            "data" => $record,
        ];
        return response()->json($response);
    }

    /**
     * show record
     * GET - api/categories/{Tag}
     */
    public function show(string $id)
    {
        $record = User::find($id);
        $response = [
            "status" => "success",
            "data" => $record,
        ];
        return response()->json($response);
    }

    /**
     * edit / update record.
     * PATCH - api/categories/{tag}
     */
    public function update(Request $request, string $tag)
    {
        $request->request->add(['id' => $tag]);

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $response = [
                "status" => "error",
                "data" => $validator->errors(),
            ];
            return response()->json($response);
        }

        $record = User::find($tag);
        $record->name = $request->name;
        $record->slug = Str::of($request->name)->slug('-');
        $record->description = $request->description;
        $record->save();
        $record = $record->fresh();

        $response = [
            "status" => "success",
            "data" => $record,
        ];
        return response()->json($response);
    }

    /**
     * destroy record.
     * DELETE - api/categories/{tag}
     */
    public function destroy(string $id)
    {
        $record = User::find($id);

        if (!$record) {
            $response = [
                "status" => "error",
                "data" => [
                    "Record does not exist"
                ],
            ];
            return response()->json($response);
        }

        $record->delete();
        $response = [
            "status" => "success",
            "data" => [],
        ];
        return response()->json($response);
    }
}
