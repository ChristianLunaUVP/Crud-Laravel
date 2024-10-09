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
            <h1 class="h3 text-primary"><i class="fas fa-list-alt"></i> Géneros</h1>
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addGeneroModal">
                <i class="fas fa-plus"></i> Nuevo Género
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered shadow-sm">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col"><i class="fas fa-id-card"></i> ID</th>
                        <th scope="col"><i class="fas fa-heading"></i> Nombre</th>
                        <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    @foreach($generos as $genero)
                        <tr>
                            <td>{{ $genero->id }}</td>
                            <td>{{ $genero->nombre }}</td>
                            <td>
                                <a href="{{ url('/generos/'.$genero->id.'/edit') }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="{{ $genero->id }}">
                                    <i class="fas fa-trash-alt"></i> Borrar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar género -->
    <div class="modal fade" id="addGeneroModal" tabindex="-1" aria-labelledby="addGeneroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="addGeneroModalLabel"><i class="fas fa-plus"></i> Agregar Nuevo Género</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/generos') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label"><i class="fas fa-heading"></i> Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
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
                    ¿Estás seguro de que deseas eliminar este género?
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
        // Pasar el ID correcto al modal de confirmación
        var confirmDeleteModal = document.getElementById('confirmDeleteModal');
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botón que activó el modal
            var generoId = button.getAttribute('data-id'); // Extraemos el ID del botón

            // Actualizamos la acción del formulario para incluir el ID correcto
            var form = document.getElementById('deleteForm');
            form.action = '/generos/' + generoId;
        });
    </script>
@endsection
