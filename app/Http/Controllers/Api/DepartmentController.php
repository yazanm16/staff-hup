<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $departmentService)
    {
        
    }
    public function index(){
        return DepartmentResource::collection($this->departmentService->getDepartmentsForIndex()['departments']);
    }
    public function store(CreateDepartmentRequest $request): JsonResponse{
        $department = $this->departmentService->storeDepartment($request->validated());
        return response()->json([
            'message' => 'Department Created Successfully',
            'data' => new DepartmentResource($department)
        ], 201);
    }

    public function edit(Department $department): JsonResponse
    {
        return response()->json([
            'data' => new DepartmentResource($department)
        ]);
    }
    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse{
        $this->departmentService->updateDepartment($department, $request->validated());
        return response()->json([
            'message' => 'Department Updated Successfully',
            'data' => new DepartmentResource($department)
        ], 200);
    }

    public function destroy(Department $department): JsonResponse
    {
        try {
            $this->departmentService->deleteDepartment($department);

            return response()->json([
                'message' => 'Department Deleted Successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}