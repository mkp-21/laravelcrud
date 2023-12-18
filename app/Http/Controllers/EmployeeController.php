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

    public function update(Employee $employee, Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'image' => 'sometimes|image:gif,png,jpeg,jpg'
        ]);


        if ( $validator->passes() ) {
             //Save data here
            
             $employee->name = $request->name;
             $employee->email = $request->email;
             $employee->address = $request->address;
             $employee->save();

            //$employee->fill($request->post())->save();

            // Upload image here
            if ($request->image) {
                $oldImage = $employee->image;

                $ext = $request->image->getClientOriginalExtension();
                $newFileName = time().'.'.$ext;
                $request->image->move(public_path().'/uploads/employee/',$newFileName); // This will save file in a folder
                
                $employee->image = $newFileName;
                $employee->save();

                File::delete(public_path().'/uploads/employee/'.$oldImage);
            }            

            return redirect()->route('employee.index')->with('success','Employee updated successfully.');


        } else {
            // return with errrors
            return redirect()->route('employee.edit',$employee->id)->withErrors($validator)->withInput();
        }

    }

    public function destroy(Employee $employee, Request $request) {
        //$employee = Employee::findOrFail($id);                
        File::delete(public_path().'/uploads/employee/'.$employee->image);
        $employee->delete();        
        return redirect()->route('employee.index')->with('success','Employee deleted successfully.');
        
    }
}
