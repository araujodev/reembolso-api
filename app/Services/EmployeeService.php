<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeService
{

    public function list(Request $request)
    {
        $employeesCollection = Employee::paginate(Employee::DEFAULT_PER_PAGE);
        return $employeesCollection;
    }
}
