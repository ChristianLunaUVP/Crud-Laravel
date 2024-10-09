@extends('layout')
@section('content')
    <div class="container mt-5">
        <!-- Mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-primary"><i class="fas fa-music"></i> Canciones</h1>
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addCancionModal">
                <i class="fas fa-plus"></i> Nueva Canción
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered shadow-sm">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col"><i class="fas fa-heading"></i> Título</th>
                        <th scope="col"><i class="fas fa-user"></i> Artista</th>
                        <th scope="col"><i class="fas fa-clock"></i> Duración</th>
                        <th scope="col"><i class="fas fa-list"></i> Género</th>
                        <th scope="col"><i class="fas fa-image"></i> Imagen</th>
                        <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($canciones as $cancion)
                        <tr>
                            <td>{{ $cancion->titulo }}</td>
                            <td>{{ $cancion->artista }}</td>
                            <td>{{ intdiv($cancion->duracion, 60) }}:{{ str_pad($cancion->duracion % 60, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $cancion->genero->nombre }}</td>
                            <td><img src="{{ asset('storage/'.$cancion->imagen) }}" alt="{{ $cancion->titulo }}" width="50" class="img-fluid rounded"></td>
                            <td>
                                <a href="{{ url('/canciones/'.$cancion->id.'/edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="{{ $cancion->id }}">
                                    <i class="fas fa-trash-alt"></i> Borrar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar canción -->
    <div class="modal fade" id="addCancionModal" tabindex="-1" aria-labelledby="addCancionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="addCancionModalLabel"><i class="fas fa-plus"></i> Agregar Nueva Canción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/canciones') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="artista" class="form-label"><i class="fas fa-user"></i> Artista</label>
                            <input type="text" class="form-control" id="artista" name="artista" required>
                        </div>
                        <div class="mb-3">
                            <label for="duracion" class="form-label"><i class="fas fa-clock"></i> Duración (mm:ss)</label>
                            <input type="text" class="form-control" id="duracion" name="duracion" placeholder="mm:ss" required>
                        </div>
                        <div class="mb-3">
                            <label for="genero_id" class="form-label"><i class="fas fa-list"></i> Género</label>
                            <select class="form-control" id="genero_id" name="genero_id" required>
                                @foreach($generos as $genero)
                                    <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label"><i class="fas fa-image"></i> Imagen</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen" required>
                            @error('imagen')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de borrado -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Borrado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta canción?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="post" action="">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger">Sí, Borrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Usamos JavaScript para pasar el ID correcto al modal de confirmación
        var confirmDeleteModal = document.getElementById('confirmDeleteModal');
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botón que activó el modal
            var cancionId = button.getAttribute('data-id'); // Extraemos el ID del botón

            // Actualizamos la acción del formulario para incluir el ID correcto
            var form = document.getElementById('deleteForm');
            form.action = '/canciones/' + cancionId;
        });
    </script>
@endsection
