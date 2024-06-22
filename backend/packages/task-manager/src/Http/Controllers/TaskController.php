<?php

namespace ProjectCode\TaskManager\Http\Controllers;

use App\Http\Controllers\Controller;
use ProjectCode\TaskManager\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * fetch records.
     * GET - api/categories
     */
    public function index()
    {
        $records = Task::get();
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
            'project_id' => 'required|numeric',
            'name' => 'required|max:255',
            'color' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'category_id' => 'required|numeric',
            'status_id' => 'required|numeric',
            'priority_id' => 'required|numeric',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $response = [
                "status" => "error",
                "data" => $validator->errors(),
            ];
            return response()->json($response);
        }

        $record = new Task();
        $record->project_id = $request->project_id;
        $record->name = $request->name;
        $record->slug = Str::of($request->name)->slug('-');
        $record->start_date = $request->start_date;
        $record->end_date = $request->end_date;
        $record->category_id = $request->category_id;
        $record->priority_id = $request->priority_id;
        $record->status_id = $request->status_id;
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
     * show record
     * GET - api/categories/{Tag}
     */
    public function show(string $id)
    {
        $record = Task::find($id);
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
    public function update(Request $request, string $record)
    {
        $request->request->add(['id' => $record]);

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

        $record = Task::find($record);
        $record->project_id = $request->project_id;
        $record->name = $request->name;
        $record->slug = Str::of($request->name)->slug('-');
        $record->start_date = $request->start_date;
        $record->end_date = $request->end_date;
        $record->category_id = $request->category_id;
        $record->priority_id = $request->priority_id;
        $record->status_id = $request->status_id;
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
        $record = Task::find($id);

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
