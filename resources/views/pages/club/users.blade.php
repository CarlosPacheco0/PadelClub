@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/users.css'])
@endpush

@section('content')
    <header>
        <h1 class="content-title">Gestión de Usuarios</h1>
    </header>

    <section class="filters">
        <div>
            <label for="search">Buscar:</label>
            <input type="text" id="search" placeholder="Nombre o correo">
        </div>
        <div>
            <label for="role">Rol:</label>
            <select id="role">
                <option value="todos">Todos</option>
                <option value="admin">Administrador</option>
                <option value="usuario">Usuario</option>
            </select>
        </div>
    </section>


    <div class="actions">
        <button class="btn btn-primary" onclick="openCreateModal()">
            Agregar usuario
        </button>
    </div>


    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->role->label }}</td>
                    <td>{{ $user->status == 1 ? 'Activo' : 'Inactivo' }}</td>
                    <td class="actions-table">
                        <button class="btn btn-edit" onclick="openEditModal({{ $user }})">
                            Editar
                        </button>

                        {{-- Eliminar registro --}}
                        <form action="{{ route('user.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button class="btn btn-delete" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Creación / Edición  --}}
    <div class="modal" id="userModal">
        <div class="modal-content">
            <h2 id="modalTitle">Nuevo Usuario</h2>

            <form id="userForm" method="POST">

                @csrf

                <input type="hidden" name="_method" id="_method">
                <input type="hidden" name="id" id="user_id">

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone" id="phone">
                </div>


                {{-- Cambiar contraseña (solo edición) --}}
                <div class="form-group" id="changePasswordWrapper" style="display:none;">
                    <label>
                        Cambiar contraseña

                        <!-- From Uiverse.io by Galahhad -->
                        <label class="switch">
                            <input checked="" type="checkbox" id="changePasswordCheckbox"
                                onchange="togglePasswordFields()">
                            <div class="slider">
                                <div class="circle">
                                    <svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512"
                                        viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path data-original="#000000" fill="currentColor"
                                                d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0">
                                            </path>
                                        </g>
                                    </svg>
                                    <svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512"
                                        viewBox="0 0 24 24" y="0" x="0" height="10" width="10"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path class="" data-original="#000000" fill="currentColor"
                                                d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </label>
                </div>

                <div id="passwordFields" style="display:none;">
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <div class="form-group">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>



                <div class="form-group">
                    <label>Rol</label>
                    <select name="role" id="role_id">
                        <option>-- Seleccione una opción --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" id="status">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-save" id="submitBtn">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('userModal');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('userForm');

        // CAMPO METODO FORM
        const _method = document.getElementById('_method');

        // CAMPO ID USUARIO
        const user_id = document.getElementById('user_id');

        // CAMPO ESTADO
        const status = document.getElementById('status');

        // CONTENEDOR CAMBIO CONTRASEÑA
        const changePass = document.getElementById('changePasswordWrapper');
        const check = document.getElementById('changePasswordCheckbox');

        // CONTENEDOR CAMPOS CONTRASEÑA
        const fieldsPass = document.getElementById('passwordFields');

        // CAMPOS CONTRASEÑA
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');



        // URL GUARDAR / EDITAR
        const URL_STORE = "{{ route('user.store') }}";
        const URL_UPDATE = "{{ route('user.update') }}";

        function togglePasswordFields() {

            fieldsPass.style.display = check.checked ? 'block' : 'none';

            password.required = check.checked;
            passwordConfirm.required = check.checked;

            if (!check) {
                password.value = '';
                passwordConfirm.value = '';
            }
        }

        function openCreateModal() {
            title.textContent = 'Nuevo Usuario';
            submitBtn.textContent = 'Guardar';

            form.reset();
            form.action = URL_STORE;
            _method.value = '';

            user_id.value = '';
            status.value = 1;

            changePass.style.display = 'none';
            fieldsPass.style.display = 'block';

            password.required = true;
            passwordConfirm.required = true;

            modal.style.display = 'flex';
        }


        function openEditModal(user) {
            title.textContent = 'Editar Usuario';
            submitBtn.textContent = 'Actualizar';

            user_id.value = user.id;
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone').value = user.phone;
            document.getElementById('role_id').value = user.role_id;
            status.value = user.status;

            form.action = URL_UPDATE;
            _method.value = 'PUT';

            // Contraseña
            changePass.style.display = 'block';
            check.checked = false;
            document.getElementById('passwordFields').style.display = 'none';

            password.required = false;
            passwordConfirm.required = false;
            password.value = '';
            passwordConfirm.value = '';

            modal.style.display = 'flex';
        }


        function closeModal() {
            modal.style.display = 'none';
        }

        // Cerrar al hacer click fuera
        // window.addEventListener('click', function(e) {
        //     if (e.target === modal) {
        //         closeModal();
        //     }
        // });
    </script>
@endsection
