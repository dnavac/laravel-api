<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentación con Swagger",
 *      description="Documentación de la API de ejemplo",
 *      @OA\Contact(
 *          email="soporte@miempresa.com"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 *
 * @OA\Server(
 *      url="/api",
 *      description="Servidor API Principal"
 * )
 */
class studentController extends Controller
{
    /**
     * @OA\Get(
     * path="/student",
     * summary="Listado de estudiantes",
     * description="Retorna un listado de estudiantes",
     * operationId="index",
     * tags={"Estudiantes"},
     * @OA\Response(
     * response=200,
     * description="Listado de estudiantes"
     * ),
     * @OA\Response(
     * response=404,
     * description="No se encontraron estudiantes"
     * )
     * )
     */
    public function index(){
        $student = Student::all();
        if($student->isEmpty()){
            return response()->json(['message'=>'No hay estudiantes'],404);
        }
        return response()->json($student,200);
    }

    /**
     * @OA\Post(
     *      path="/student",
     *      operationId="createStudent",
     *      tags={"Estudiantes"},
     *      summary="Crear un nuevo estudiante",
     *      description="Registra un nuevo estudiante en el sistema.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","phone","language"},
     *              @OA\Property(property="name", type="string", example="Juan Pérez"),
     *              @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *              @OA\Property(property="phone", type="string", example="1234567890"),
     *              @OA\Property(property="language", type="string", example="Español")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Estudiante creado exitosamente"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error de validación"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error al crear el estudiante"
     *      )
     * )
     */
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
    /**
     * @OA\Get(
     *      path="/student/{id}",
     *      operationId="getStudentById",
     *      tags={"Estudiantes"},
     *      summary="Obtener un estudiante por ID",
     *      description="Obtiene la información de un estudiante específico.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del estudiante",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Estudiante encontrado"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No se encontró el estudiante"
     *      )
     * )
     */
    public function show($id){
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No se encontró el estudiante'],404);
        }
        return response()->json($student,200);
    }

    /**
     * @OA\Put(
     *      path="/student/{id}",
     *      operationId="updateStudent",
     *      tags={"Estudiantes"},
     *      summary="Actualizar un estudiante",
     *      description="Actualiza los datos de un estudiante existente.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del estudiante",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","phone","language"},
     *              @OA\Property(property="name", type="string", example="Juan Pérez"),
     *              @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *              @OA\Property(property="phone", type="string", example="1234567890"),
     *              @OA\Property(property="language", type="string", example="Español")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Estudiante actualizado correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No se encontró el estudiante"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error de validación"
     *      )
     * )
     */
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

    /**
     * @OA\Delete(
     *      path="/student/{id}",
     *      operationId="deleteStudent",
     *      tags={"Estudiantes"},
     *      summary="Eliminar un estudiante",
     *      description="Elimina un estudiante del sistema.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del estudiante",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Estudiante eliminado correctamente"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No se encontró el estudiante"
     *      )
     * )
     */
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
