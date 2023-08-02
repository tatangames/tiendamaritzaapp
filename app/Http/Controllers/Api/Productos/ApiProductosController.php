<?php

namespace App\Http\Controllers\Api\Productos;

use App\Http\Controllers\Controller;
use App\Models\HistorialPrecios;
use App\Models\Productos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class ApiProductosController extends Controller{


    public function buscarProducto(Request $request){


        $data = Productos::where('nombre', 'LIKE', "%{$request->nombre}%")->get();

        /*$hayproducto = true;
        if(sizeof($data) == 0){
            $hayproducto = false;
        }*/

        foreach ($data as $dd){

            if(isEmpty($dd->codigo)){
                $dd->nombreunido = $dd->nombre;
            }else{
                $dd->nombreunido = $dd->nombre . " - " . $dd->codigo;
            }

            $dd->precio = '$ ' . number_format((float)$dd->precio, 2, '.', ',');
        }

        return ['success' => 1, 'productos' => $data];
    }


    public function registrarProducto(Request $request){

        $reglaDatos = array(
            'nombre' => 'required',
            'precio' => 'required'
        );

        // codigo

        $validarDatos = Validator::make($request->all(), $reglaDatos );

        if($validarDatos->fails()){return ['success' => 0]; }


        $dato = new Productos();
        $dato->nombre = $request->nombre;
        $dato->codigo = $request->codigo;
        $dato->precio = $request->precio;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }



    public function actualizarProductoPrecio(Request $request){

        $rules = array(
            'id' => 'required',
            'nombre' => 'required',
            'precio' => 'required'
        );

        // codigo

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($info = Productos::where('id', $request->id)->first()){

            if($info->precio == $request->precio){

                // NO SE ACTUALIZO EL PRECIO
                Productos::where('id', $request->id)
                    ->update(['nombre' => $request->nombre,
                              'codigo' => $request->codigo]);

                return ['success' => 1];
            }else{

                // SI ACTUALIZO PRECIO, GUARDAR HISTORIAL

                Productos::where('id', $request->id)
                    ->update(['nombre' => $request->nombre,
                        'codigo' => $request->codigo,
                        'precio' => $request->precio]);

                $fecha = Carbon::now('America/El_Salvador');

                $dato = new HistorialPrecios();
                $dato->id_productos = $info->id;
                $dato->precio = $request->precio;
                $dato->fecha = $fecha;
                $dato->save();

                return ['success' => 1];
            }

        }else{
            return ['success' => 99];
        }
    }



    public function historialPreciosProducto(Request $request){

        $rules = array(
            'id' => 'required',
        );

        // codigo

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($info = Productos::where('id', $request->id)->first()){

            $lista = HistorialPrecios::where('id_productos', $info->id)
                ->orderBy('fecha', 'DESC')
                ->get();

            foreach ($lista as $dato){

                $dato->fecha = date("d-m-Y", strtotime($dato->fecha));
                $dato->precio = '$' . number_format((float)$dato->precio, 2, '.', ',');

                $dato->nombre = $info->nombre;
            }

            return ['success' => 1, 'productos' => $lista];

        }else{
            return ['success' => 99];
        }
    }



    public function informacionProducto(Request $request){

        $rules = array(
            'id' => 'required',
        );

        // codigo

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($info = Productos::where('id', $request->id)->first()){

            return ['success' => 1, 'nombre' => $info->nombre,
                'codigo' => $info->codigo,
                'precio' => $info->precio];

        }else{
            return ['success' => 99];
        }
    }





}
