@extends('layout')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-edit"></i> Editar Canción
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ url('/canciones/'.$canciones->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PATCH') }}
                            <div class="mb-3">
                                <label for="titulo" class="form-label"><i class="fas fa-music"></i> Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $canciones->titulo }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="artista" class="form-label"><i class="fas fa-user"></i> Artista</label>
                                <input type="text" class="form-control" id="artista" name="artista" value="{{ $canciones->artista }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="duracion" class="form-label"><i class="fas fa-clock"></i> Duración (mm:ss)</label>
                                <input type="text" class="form-control" id="duracion" name="duracion" value="{{ intdiv($canciones->duracion, 60) }}:{{ str_pad($canciones->duracion % 60, 2, '0', STR_PAD_LEFT) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="genero_id" class="form-label"><i class="fas fa-list"></i> Género</label>
                                <select class="form-control" id="genero_id" name="genero_id" required>
                                    @foreach($generos as $genero)
                                        <option value="{{ $genero->id }}" {{ $genero->id == $canciones->genero_id ? 'selected' : '' }}>{{ $genero->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="imagen" class="form-label"><i class="fas fa-image"></i> Imagen</label>
                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen">
                                @error('imagen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if($canciones->imagen)
                                    <img src="{{ asset('storage/'.$canciones->imagen) }}" alt="{{ $canciones->titulo }}" width="100" class="mt-2">
                                    <form action="{{ url('/canciones/'.$canciones->id.'/delete-image') }}" method="post" style="display:inline;">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-danger mt-2"><i class="fas fa-trash-alt"></i> Borrar Imagen</button>
                                    </form>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection