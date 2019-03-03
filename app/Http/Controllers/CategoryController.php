<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoriesBasicDatatableFormat()
    {
        $categories = Category::all();

        //Filtramos y armamos la data
        $data = array();
        foreach ($categories as $index => $category)
        {
            $btnEliminar = '<button title="Eliminar categoría" onclick="eliminarCategoria('.$category->id.', \''.mb_strtoupper($category->name).'\')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></span>';
            $btnEditar = "";
            $accion = $btnEditar.' '. $btnEliminar;

            array_push($data, [
                'index'         => ($index+1),
                'nombre'        => $category->name,
                'descripcion'   => is_null($category->description) ? 'NO PRESENTA' : $category->description,
                'accion'        => $accion
            ]);
        }

        return response()->json(["data" => $data], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if(is_null($category))
        {
            return response()->json(["success" => false, "error" => "No se encontró el id"]);
        }

        $category->delete();
        return response()->json(["success" => true, "data" => $category]);
    }
}
