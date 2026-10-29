 @extends('layouts.admin')

 @section('content')
     <div class="container-fluid clients-page">

         {{-- Encabezado --}}
         <div class="clients-heading mb-4">
             <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                 <div>
                     <div class="small mb-2">
                         <i class="bi bi-people-fill me-1"></i>
                         GESTIÓN DE CLIENTES
                     </div>

                     <h1 class="h3 fw-bold mb-2">Clientes</h1>

                     <p class="mb-0">
                         Consultá y administrá los datos de tus clientes registrados.
                     </p>
                 </div>

                 <a href="{{ route('clients.create') }}" class="btn btn-register">
                     <i class="bi bi-person-plus-fill me-1"></i>
                     Registrar cliente
                 </a>
             </div>
         </div>

         {{-- Mensaje de éxito --}}
         @if (session('success'))
             <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                 <i class="bi bi-check-circle-fill me-2"></i>
                 {{ session('success') }}
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
             </div>
         @endif

         {{-- Buscador --}}
         <div class="clients-card mb-4">
             <div class="section-heading">
                 <div class="d-flex align-items-center">
                     <span class="section-icon">
                         <i class="bi bi-search"></i>
                     </span>

                     <div>
                         <h5 class="mb-1 fw-bold">Buscar clientes</h5>
                         <small class="text-muted">
                             Ingresá el DNI para encontrar un cliente rápidamente.
                         </small>
                     </div>
                 </div>
             </div>

             <div class="card-body p-4">
                 <form action="{{ route('clients.index') }}" method="GET">
                     <label for="search" class="search-label">DNI del cliente</label>

                     <div class="row g-2">
                         <div class="col-12 col-md">
                             <input type="number" min="0" name="search" id="search" class="form-control"
                                 placeholder="Ej.: 35123456" value="{{ request('search') }}">
                         </div>

                         <div class="col-6 col-md-auto">
                             <button type="submit" class="btn btn-search w-100 h-100 px-4">
                                 <i class="bi bi-search me-1"></i>
                                 Buscar
                             </button>
                         </div>

                         <div class="col-6 col-md-auto">
                             <a href="{{ route('clients.index') }}"
                                 class="btn btn-outline-secondary btn-clear w-100 h-100 d-flex align-items-center justify-content-center px-3">
                                 <i class="bi bi-arrow-counterclockwise me-1"></i>
                                 Limpiar
                             </a>
                         </div>
                     </div>
                 </form>
             </div>
         </div>

         {{-- Tabla de clientes --}}
         <div class="clients-card">
             <div class="section-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
                 <div class="d-flex align-items-center">
                     <span class="section-icon">
                         <i class="bi bi-person-lines-fill"></i>
                     </span>

                     <div>
                         <h5 class="mb-1 fw-bold">Listado de clientes</h5>
                         <small class="text-muted">
                             Clientes activos registrados en el sistema.
                         </small>
                     </div>
                 </div>

                 <span class="badge rounded-pill px-3 py-2" style="background:#142b4a;color:#fff">
                     {{ $clients->total() }} cliente(s)
                 </span>
             </div>

             <div class="table-responsive">
                 <table class="table align-middle">
                     <thead>
                         <tr>
                             <th>Nombre completo</th>
                             <th>DNI</th>
                             <th>Teléfono</th>

                             @if (auth()->user()->role->name === 'Administrador')
                                 <th class="text-center">Acciones</th>
                             @endif
                         </tr>
                     </thead>

                     <tbody>
                         @forelse ($clients as $client)
                             <tr id="client-{{ $client->id }}">
                                 <td>
                                     <div class="d-flex align-items-center gap-3">
                                         <span class="client-avatar">
                                             {{ mb_strtoupper(mb_substr($client->full_name, 0, 1)) }}
                                         </span>

                                         <span class="client-name">
                                             {{ $client->full_name }}
                                         </span>
                                     </div>
                                 </td>

                                 <td>
                                     <span class="client-dni">{{ $client->dni }}</span>
                                 </td>

                                 <td>
                                     @if ($client->phone)
                                         <i class="bi bi-telephone-fill text-secondary me-1"></i>
                                         {{ $client->phone }}
                                     @else
                                         <span class="text-muted">No registrado</span>
                                     @endif
                                 </td>

                                 @if (auth()->user()->role->name === 'Administrador')
                                     <td class="text-center">
                                         <div class="d-flex flex-wrap justify-content-center gap-2">
                                             <a href="{{ route('clients.edit', $client->id) }}"
                                                 class="btn btn-sm btn-edit">
                                                 <i class="bi bi-pencil-square me-1"></i>
                                                 Editar
                                             </a>

                                             <button type="button" onclick="deleteClient({{ $client->id }})"
                                                 class="btn btn-sm btn-delete">
                                                 <i class="bi bi-trash3 me-1"></i>
                                                 Eliminar
                                             </button>
                                         </div>
                                     </td>
                                 @endif
                             </tr>
                         @empty
                             <tr>
                                 <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 4 : 3 }}">
                                     <div class="empty-state">
                                         <div class="empty-icon">
                                             <i class="bi bi-person-x"></i>
                                         </div>

                                         <h5 class="fw-bold mb-2">No se encontraron clientes</h5>

                                         <p class="text-muted mb-3">
                                             @if (request('search'))
                                                 No hay clientes activos que coincidan con ese DNI.
                                             @else
                                                 Todavía no hay clientes registrados para mostrar.
                                             @endif
                                         </p>

                                         @if (request('search'))
                                             <a href="{{ route('clients.index') }}" class="btn btn-outline-primary">
                                                 <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                 Ver todos los clientes
                                             </a>
                                         @endif
                                     </div>
                                 </td>
                             </tr>
                         @endforelse
                     </tbody>
                 </table>
             </div>

             {{-- Paginación --}}
             @if ($clients->hasPages() || $clients->total() > 0)
                 <div class="results-footer justify-content-between align-items-center gap-3">
                     {{ $clients->withQueryString()->links() }}
                 </div>
             @endif
         </div>
     </div>

     {{-- SweetAlert2 --}}
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script>
         function deleteClient(id) {
             Swal.fire({
                 title: '¿Eliminar cliente?',
                 text: 'Sus datos e historial se conservarán.',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#a83240',
                 cancelButtonColor: '#344b65',
                 confirmButtonText: 'Sí, eliminar',
                 cancelButtonText: 'Cancelar',
                 reverseButtons: true
             }).then((result) => {
                 if (!result.isConfirmed) {
                     return;
                 }

                 fetch(`/clients/${id}/deactivate`, {
                         method: 'PUT',
                         headers: {
                             'X-CSRF-TOKEN': '{{ csrf_token() }}',
                             'Accept': 'application/json',
                             'Content-Type': 'application/json'
                         }
                     })
                     .then(response => {
                         if (!response.ok) {
                             throw new Error(
                                 'No se pudo eliminar el cliente. Verificá los permisos e intentá nuevamente.'
                                 );
                         }

                         return response.json();
                     })
                     .then(data => {
                         if (data.success) {
                             Swal.fire({
                                 title: '¡Cliente eliminado!',
                                 text: 'El cliente fue quitado del listado y su historial se conservó.',
                                 icon: 'success',
                                 confirmButtonColor: '#142b4a',
                                 confirmButtonText: 'Aceptar'
                             }).then(() => {
                                 location.reload();
                             });
                         } else {
                             throw new Error('El sistema no confirmó la eliminación del cliente.');
                         }
                     })
                     .catch(error => {
                         Swal.fire({
                             title: 'Error',
                             text: error.message,
                             icon: 'error',
                             confirmButtonColor: '#142b4a'
                         });
                     });
             });
         }
     </script>
 @endsection
