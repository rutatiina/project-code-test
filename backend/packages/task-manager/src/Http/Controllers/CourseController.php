<?php

namespace TaskManager\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use TaskManager\Models\Course;
use TaskManager\Models\School;
use TaskManager\Models\Faculty;
use App\Http\Controllers\Controller;
use TaskManager\Models\Department;
use TaskManager\Models\University;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use TaskManager\Models\LibraryResource;
use TaskManager\Traits\ImageManagerTrait;
use Illuminate\Support\Facades\Response as FacadeResponse;

class CourseController extends Controller
{

    use ImageManagerTrait;

    // >> get the item attributes template << !!important

    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $q = Course::query();

        if ($request->search) {
            $q->where(function ($qr) use ($request) {
                $qr->where('name', 'like', '%' . $request->search . '%');
                $qr->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if (!$request->sort_label) $q->orderBy('name');
        if ($request->sort_label && $request->sort_order == "asc") $q->orderBy($request->sort_label);
        if ($request->sort_label && $request->sort_order == "desc") $q->orderByDesc($request->sort_label);

        return $q->paginate(10);
    }

    public function create()
    {
        return [
            "universities" => University::select(["id", "name"])->get(),
            "schools" => School::select(["id", "name", "university_id"])->get(),
            "faculties" => Faculty::get()->setVisible(["id", "name", "university_id", "school_id"]),
            "departments" => Department::get()->setVisible(["id", "name", "university_id", "school_id", "faculty_id"]),
        ];
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                Rule::unique((new Course)->getTable(), 'name')
                    ->where(function (Builder $query) use ($request) {
                        $query->where('university_id', $request->university_id);
                        $query->where('school_id', $request->school_id);
                        $query->where('faculty_id', $request->faculty_id);
                        $query->where('department_id', $request->department_id);
                    }),
            ],
            'university_id' => 'required|numeric|gt:0',
            'school_id' => 'required|numeric|min:0',
            'faculty_id' => 'required|numeric|min:0',
            'department_id' => 'required|numeric|min:0',
        ];

        $messages = [
            'university_id.required' => 'Please select a university.',
            'university_id.numeric' => 'Please select a university.',
            'university_id.gt' => 'Please select a university.',

            'school_id.required' => 'Please select a value.',
            'school_id.numeric' => 'Please select a school.',
            'school_id.gt' => 'Please select a school.',

            'faculty_id.required' => 'Please select a value.',
            'faculty_id.numeric' => 'Please select a faculty.',
            'faculty_id.gt' => 'Please select a faculty.',

            'department_id.required' => 'Please select a value.',
            'department_id.numeric' => 'Please select a department.',
            'department_id.gt' => 'Please select a department.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $course = new Course();
        $course->university_id = $request->university_id;
        $course->faculty_id = $request->faculty_id;
        $course->school_id = $request->school_id;
        $course->department_id = $request->department_id;
        $course->name = $request->name;
        $course->type = $request->type;
        $course->slug = Str::of($request->name)->slug('-');
        $course->description = $request->description;
        $course->credits = $request->credits;
        $course->semester = $request->semester;
        $course->year = $request->year;
        $course->save();
        $course->refresh();

        return [
            'code' => Response::HTTP_OK,
            "message" => "Course saved successfully",
            "payload" => $course,
            'callback' => "/courses/" . $course->id,
        ];
    }

    public function show($id)
    {
        return Course::find($id);
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|exists:TaskManager\Models\Course,id',
            'name' => [
                'required',
                'string',
                Rule::unique((new Course)->getTable(), 'name')
                    ->ignore($request->id)
                    ->where(function (Builder $query) use ($request) {
                        $query->where('university_id', $request->university_id);
                        $query->where('school_id', $request->school_id);
                        $query->where('faculty_id', $request->faculty_id);
                        $query->where('department_id', $request->department_id);
                    }),
            ],
            'university_id' => 'required|numeric|gt:0',
            'school_id' => 'required|numeric|min:0',
            'faculty_id' => 'required|numeric|min:0',
            'department_id' => 'required|numeric|min:0',
        ];

        $messages = [
            'university_id.required' => 'Please select a university.',
            'university_id.numeric' => 'Please select a university.',
            'university_id.gt' => 'Please select a university.',

            'school_id.required' => 'Please select a value.',
            'school_id.numeric' => 'Please select a school.',
            'school_id.gt' => 'Please select a school.',

            'faculty_id.required' => 'Please select a value.',
            'faculty_id.numeric' => 'Please select a faculty.',
            'faculty_id.gt' => 'Please select a faculty.',

            'department_id.required' => 'Please select a value.',
            'department_id.numeric' => 'Please select a department.',
            'department_id.gt' => 'Please select a department.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $course = Course::find($request->id);
        $course->university_id = $request->university_id;
        $course->faculty_id = $request->faculty_id;
        $course->school_id = $request->school_id;
        $course->department_id = $request->department_id;
        $course->name = $request->name;
        $course->type = $request->type;
        $course->slug = Str::of($request->name)->slug('-');
        $course->description = $request->description;
        $course->credits = $request->credits;
        $course->semester = $request->semester;
        $course->year = $request->year;
        $course->save();
        $course->refresh();

        return [
            'code' => Response::HTTP_OK,
            "message" => "Course updated successfully",
            "payload" => $course,
            'callback' => "/courses/" . $course->id,
        ];
    }

    public function destroy($id)
    {
        $reponse = [
            "message" => "",
            "errors" => []
        ];

        $course = Course::find($id);

        //check if the course has library resources
        if (LibraryResource::where('course_id', $course->id)->count() > 0) {
            $reponse['message'] = 'Course has resources in library';
            $reponse['errors'] = [
                'units' => [
                    'Course has resources in library'
                ]
            ];
            return response($reponse, 422)->header('Content-Type', 'application/json');
        }

        //means all checks have been passed so delete the university
        return $course->delete();
    }

    public function importTemplate()
    {
        $hiddenColumns = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'university_id',
            'school_id',
            'faculty_id',
            'department_id',
            'slug',
            'cover_image',
            'status',
            'active',
        ];

        $columns = Schema::getColumnListing((new Course)->getTable());
        $csvFileName = 'import-courses-template.csv';

        $headerColumns = array_diff($columns, $hiddenColumns);

        // $headers = [
        //     'Content-Type' => 'application/octet-stream', // 'text/csv',
        //     'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        // ];

        // $handle = fopen('php://output', 'w');
        // fputcsv($handle, $headerColumns); // Add more headers as needed
        // $content = stream_get_contents($handle);
        // fclose($handle);

        // return FacadeResponse::make('', 200, $headers);

        return response()->streamDownload(function () use ($headerColumns) {
            $handle = fopen('php://output', 'w'); //php://output is a write-only stream that allows you to write to the output buffer mechanism in the same way as print and echo
            fputcsv($handle, $headerColumns); // Add more headers as needed
            fclose($handle);
        }, $csvFileName);
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => ['required', 'file', 'csv'],
            'university_id' => 'required|numeric|gt:0',
            'school_id' => 'required|numeric|min:0',
            'faculty_id' => 'required|numeric|min:0',
            'department_id' => 'required|numeric|min:0',
        ];

        $messages = [
            'university_id.required' => 'Please select a university.',
            'university_id.numeric' => 'Please select a university.',
            'university_id.gt' => 'Please select a university.',

            'school_id.required' => 'Please select a value.',
            'school_id.numeric' => 'Please select a school.',
            'school_id.gt' => 'Please select a school.',

            'faculty_id.required' => 'Please select a value.',
            'faculty_id.numeric' => 'Please select a faculty.',
            'faculty_id.gt' => 'Please select a faculty.',

            'department_id.required' => 'Please select a value.',
            'department_id.numeric' => 'Please select a department.',
            'department_id.gt' => 'Please select a department.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $file = $request->file('file');
        $fileContents = file($file->getPathname());

        $i = 1;
        $data = [];
        foreach ($fileContents as $index => $line) {
            if ($index == 0) continue;

            $i++;

            $row = str_getcsv($line);
            $data[$i] = [
                'university_id' => $request->university_id,
                'faculty_id' => $request->faculty_id,
                'school_id' => $request->school_id,
                'department_id' => $request->department_id,

                'name' => $row[0],
                'type' => $row[1],
                'description' => $row[2],
                'credits' => $row[3],
                'semester' => $row[4],
                'year' => $row[5],
                // 'active' => $request->active,
            ];
        }

        // return $data;

        $rules = [
            'rows' => 'required|array',
            'rows.*.name' => [
                'required',
                'distinct',
                'string',
                Rule::unique((new Course)->getTable(), 'name')
                    ->where(function (Builder $query) use ($request) {
                        $query->where('university_id', $request->university_id);
                        $query->where('school_id', $request->school_id);
                        $query->where('faculty_id', $request->faculty_id);
                        $query->where('department_id', $request->department_id);
                    }),
            ],
        ];

        $messages = [
            'rows.required' => 'The file uploaded is empty.',
            // 'rows.*.name.distinct' => 'The :attribute value has a duplicate :input.',
        ];

        Validator::make(['rows' => $data], $rules, $messages)->validate();

        Course::insert($data);


        return [
            'code' => Response::HTTP_OK,
            "message" => "Courses imported successfully",
        ];
    }
}
