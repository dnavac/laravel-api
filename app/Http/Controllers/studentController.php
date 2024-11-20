<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index(){
        $student = Student::all();
        if($student->isEmpty()){
            return response()->json(['message'=>'No hay estudiantes'],404);
        }
        return response()->json($student,200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required|email|unique:student',
            'phone'=>'required|digits:10',
            'language'=>'required'
        ]);
        if($validator->fails()){
            $data=[
                'message'=>'Error de validación',
                'errors'=>$validator->errors(),
                'status_code'=>400
            ];
            return response()->json($data,400);
        }
        $student= student::create(([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'language'=>$request->language
        ]));
        if(!$student){
            $data=[
                'message'=>'Error al crear el estudiante',
                'status_code'=>500
            ];
            return response()->json($data,500);
        }
        return response()->json($student,201);
    }
    public function show($id){
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No se encontró el estudiante'],404);
        }
        return response()->json($student,200);
    }
    public function update(Request $request,$id){
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No se encontró el estudiante'],404);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required|email|unique:student,email,'.$id,
            'phone'=>'required|digits:10',
            'language'=>'required'
        ]);
        if($validator->fails()){
            $data=[
                'message'=>'Error de validación',
                'errors'=>$validator->errors(),
                'status_code'=>400
            ];
            return response()->json($data,400);
        }
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data= [
            'message'=>'Estudiante guardado correctamente',
            'student'=>$student,
            'status_code'=>200
        ];
        return response()->json($data,200);


    }

    public function destroy($id){
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No se encontró el estudiante'],404);
        }
        if($student->delete()){
            return response()->json(['message'=>'Estudiante eliminado correctamente'],200);
        }
    }

}
