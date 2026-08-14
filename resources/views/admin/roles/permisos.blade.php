@extends('admin.layouts.app')

@section('title','Asignar Permisos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">

            <h4 class="page-title">
                Asignar Permisos
            </h4>

        </div>

        <a href="{{ route('admin.roles.index') }}" class="btn btn-dark btn-round">
            <i class="fa fa-arrow-left"></i>
            Regresar
        </a>

    </div>


    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-1">
                Rol:
                <span class="text-primary">
                    {{ $rol->nombre }}
                </span>
            </h4>

            <small class="text-muted">
                Seleccione los permisos que tendrá asignado este rol
            </small>

        </div>


        <div class="card-body">

            <form action="{{ route('admin.roles.permisos.guardar',$rol->id_rol) }}" method="POST">

                @csrf

                <div class="row">


                    @foreach($permisos->groupBy(function($permiso){

                        return explode('.',$permiso->codigo)[0];

                    }) as $modulo => $listaPermisos)


                    <div class="col-md-4 mb-4">


                        <div class="card border shadow-sm h-100">


                            <div class="card-header bg-light">

                                <h5 class="mb-0 text-uppercase">

                                    <i class="fa fa-folder text-warning"></i>

                                    {{ ucfirst($modulo) }}

                                </h5>

                            </div>


                            <div class="card-body">


                                @foreach($listaPermisos as $permiso)


                                <div class="form-check mb-3">


                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="permisos[]"
                                           value="{{ $permiso->id_permiso }}"
                                           id="permiso{{ $permiso->id_permiso }}"

                                           {{ in_array($permiso->id_permiso,$permisosAsignados) ? 'checked':'' }}>


                                    <label class="form-check-label"
                                           for="permiso{{ $permiso->id_permiso }}">


                                        <span class="fw-semibold">

                                            {{ $permiso->nombre }}

                                        </span>


                                        <br>


                                        <small class="text-muted">

                                            {{ $permiso->codigo }}

                                        </small>


                                    </label>


                                </div>


                                @endforeach


                            </div>


                        </div>


                    </div>


                    @endforeach


                </div>


                <div class="text-end mt-3">

                    <button type="submit" class="btn btn-success btn-round">

                        <i class="fa fa-save"></i>

                        Guardar Permisos

                    </button>

                </div>


            </form>


        </div>


    </div>


</div>

@endsection