<?php

namespace App\Http\Controllers;

use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productsDatatableServerSideFormat(Request $request)
    {
        //Recibo parámetros personalizados para los filtros
        $category = $request->categoryId;


        /* Parámetros mandados internamente por el pluggin dataTables */
        $draw = $request->draw;
        $inicio = $request->start;
        $sizePag = $request->length;
        $columns = $request->columns;

        //Columnas de orden
        $orders = $request->order;

        $products = Product::with('category');
        $recordsTotal = $products->count();

        //Filtros

        if(!is_null($category))
        {
            $products->where('category_id', $category);
        }


        $recordsFiltered = $products->count();

        //Ordenamos
        if(!is_null($orders))
        {
            foreach ($orders as $order)
            {
                $index = $order['column'];
                $columnName = $columns[$index]['data'];
                $dir = $order['dir'];

                //Aca ya hago mi condicional
                switch ($columnName)
                {
                    case 'nombre':
                        $products->orderBy('name', $dir);
                        break;
                    case 'stock':
                        $products->orderBy('stock', $dir);
                        break;
                }
            }
        }

        //paginado y obtención de los datos
        $products = $products->offset($inicio)->limit($sizePag)->get();



        $data = [];
        /* Formateao y elijo los datos para enviar */
        foreach ($products as $product)
        {
            $precioCompra = 'S/. '.str_pad(number_format( $product->buy_price, 2), 10, "*", STR_PAD_LEFT);
            $precioVenta = 'S/. '.str_pad(number_format( $product->sell_price, 2), 10, "*", STR_PAD_LEFT);

            $btnEliminar = '<button title="Eliminar producto" onclick="eliminarProducto('.$product->id.', \''.mb_strtoupper($product->name).'\')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></span>';
            $btnEditar = "";
            $accion = $btnEditar.' '. $btnEliminar;

            array_push($data, [
                "nombre"                => $product->name,
                "precio_compra"         => $precioCompra,
                "precio_venta"          => $precioVenta,
                "categoria"             => mb_strtoupper($product->category->name),
                "stock"                 => $product->stock,
                "accion"                => $accion
            ]);
        }

        $fechaServidor = Carbon::now()->format('d/m/Y g:i A');

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            "data" => $data,
            "fechaServidor" => $fechaServidor/*Parámetro adicional, no necesario*/
        ], 200);
    }

    function destroy($id)
    {
        $product = Product::find($id);
        if(is_null($product))
        {
            return response()->json(["success" => false, "error" => "No se encontró el id"]);
        }

        $product->delete();
        return response()->json(["success" => true, "data" => $product]);
    }
}
