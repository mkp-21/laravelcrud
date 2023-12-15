<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\employee;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    public function index(){

        $employees = employee::orderBy('id','DESC')->paginate(5);
        return view('employee.list',['employees' => $employees]);
    }

    public function create(){
        return view('employee.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'image' => 'sometimes|image:gif,png,jpeg,jpg'
        ]);

        if($validator->passes()){
            $employee =new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->save();

            if($request->image){
                $ext=$request->image->getClientOriginalExtension();
                $NewFileName= time().'.'.$ext;
                $request->image->move(public_path().'/uploads/employee/',$NewFileName);

                $employee->image = $NewFileName;
                $employee->save();
            }

            return redirect()->route('employee.index')->with('success','Employee added successfully.');
        } else {

        return redirect()->route('employee.create')->withErrors($validator)->withInput();
            
        }
    }

    public function edit(Employee $employee) {
        //$employee = Employee::findOrFail($id);       
        return view('employee.edit',['employee' => $employee]);
    }
}
