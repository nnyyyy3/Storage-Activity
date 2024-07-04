<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $employee = new Employee();
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');

        if ($request->hasFile('photo')) {
            Log::info('Photo upload detected.');
            $file = $request->file('photo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'employee_photos/' . $fileName;

            try {
                Storage::disk('employee_photos')->put($fileName, file_get_contents($file));
                $employee->photo = $filePath;
                Log::info('Photo saved successfully at ' . $filePath);
            } catch (\Exception $e) {
                Log::error('Error saving photo: ' . $e->getMessage());
            }
        } else {
            Log::info('No photo uploaded.');
        }

        try {
            $employee->save();
            Log::info('Employee saved successfully.');
        } catch (\Exception $e) {
            Log::error('Error saving employee: ' . $e->getMessage());
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.show', compact('employee'));
    }
}
