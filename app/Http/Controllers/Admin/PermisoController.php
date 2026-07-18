<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermisoController extends Controller
{

    public function index()
    {
        $permisos = Permiso::orderBy('id_permiso', 'asc')->get();
        return view('admin.permisos.index', compact('permisos'));
    }



    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|max:100',
            'codigo' => 'required|max:100|unique:permisos,codigo',
            'descripcion' => 'nullable|max:255'
        ]);


        DB::beginTransaction();
        try {
            Permiso::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'estado' => 1
            ]);

            DB::commit();
            return redirect()->route('admin.permisos.index')->with('success','Permiso creado correctamente');
        } catch(\Exception $e){
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    public function update(Request $request,$id_permiso)
    {
        $permiso = Permiso::findOrFail($id_permiso);

        $request->validate([
            'nombre'=>'required|max:100',
            'codigo'=>'required|max:100|unique:permisos,codigo,'.$id_permiso.',id_permiso',
            'descripcion'=>'nullable|max:255'
        ]);

        DB::beginTransaction();

        try {
            $permiso->update([
                'nombre'=>$request->nombre,
                'codigo'=>$request->codigo,
                'descripcion'=>$request->descripcion,
                'estado'=>$request->estado ?? 1
            ]);

            DB::commit();
            return redirect()->route('admin.permisos.index')->with('success','Permiso actualizado correctamente');

        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    public function destroy($id_permiso)
    {
        $permiso = Permiso::findOrFail($id_permiso);
        $permiso->delete();
        return back()->with('delete','Permiso eliminado correctamente');
    }


    public function cambiarEstado($id_permiso)
    {
        $permiso = Permiso::findOrFail($id_permiso);
        $permiso->update([
            'estado'=>$permiso->estado == 1 ? 0 : 1
        ]);
        return back()->with('success','Estado actualizado correctamente');
    }

}