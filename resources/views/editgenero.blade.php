@extends('layout')
@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-edit me-2"></i> Editar Género
            </div>
            <div class="card-body">
                <form action="{{ url('/generos/'.$genero->id) }}" method="post">
                    @csrf
                    {{ method_field('PATCH') }}
                    <div class="mb-3">
                        <label for="nombre" class="form-label"><i class="fas fa-music"></i> Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $genero->nombre }}" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
