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
use App\Traits\ApiResponse;

class DepartmentController extends Controller
{
    use ApiResponse;
    public function __construct(protected DepartmentService $departmentService)
    {
        
    }
    public function index(){
        return DepartmentResource::collection($this->departmentService->getDepartmentsForIndex()['departments']);
    }
    public function store(CreateDepartmentRequest $request): JsonResponse{
        $department = $this->departmentService->storeDepartment($request->validated());
        return $this->success([new DepartmentResource($department)], 'Department Created Successfully', 201);
    }

    public function edit(Department $department): JsonResponse
    {
        return $this->success([
            'data' => new DepartmentResource($department)
        ]);
    }
    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse{
        $this->departmentService->updateDepartment($department, $request->validated());
        return $this->success([
            'message' => 'Department Updated Successfully',
            'data' => new DepartmentResource($department)
        ], 200);
    }

    public function destroy(Department $department): JsonResponse
    {
        $this->departmentService->deleteDepartment($department);

        return $this->success([], 'Department Deleted Successfully', 200);
    }
}