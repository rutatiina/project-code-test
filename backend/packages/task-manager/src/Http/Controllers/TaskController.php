<?php

namespace ProjectCode\TaskManager\Http\Controllers;

use App\Http\Controllers\Controller;
use ProjectCode\TaskManager\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use ProjectCode\TaskManager\Models\TaskMember;

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
            'members' => 'required|array',
            'members.*' => 'required|numeric',
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
        

        //save the members
        foreach($request->members as $userId) {
            $recordMember = new TaskMember();
            $recordMember->project_id = $request->project_id;
            $recordMember->task_id = $record->id;
            $recordMember->user_id = $userId;
            $recordMember->save();
        }

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
            'id' => 'required|exists:tasks,id',
            'project_id' => 'numeric',
            'name' => 'max:255',
            'color' => 'max:255',
            'start_date' => 'date',
            'end_date' => 'date',
            'category_id' => 'numeric',
            'status_id' => 'numeric',
            'priority_id' => 'numeric',
            'description' => 'max:255',
        ]);

        if ($validator->fails()) {
            $response = [
                "status" => "error",
                "data" => $validator->errors(),
            ];
            return response()->json($response);
        }

        $record = Task::find($record);
        if ($request->project_id) $record->project_id = $request->project_id;
        if ($request->name) $record->name = $request->name;
        if ($request->name) $record->slug = Str::of($request->name)->slug('-');
        if ($request->start_date) $record->start_date = $request->start_date;
        if ($request->end_date) $record->end_date = $request->end_date;
        if ($request->category_id) $record->category_id = $request->category_id;
        if ($request->priority_id) $record->priority_id = $request->priority_id;
        if ($request->status_id) $record->status_id = $request->status_id;
        if ($request->description) $record->description = $request->description;
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
