@extends('template')

@section('section-template')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-xl-12">
                    <div class="card h-xl-100">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_user">
                                        <span class="svg-icon svg-icon-2" style="color: #fff;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                    rx="1" transform="rotate(-90 11.364 20.364)"
                                                    fill="currentColor" />
                                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        Añadir nuevo usuario
                                    </button>
                                </div>
                                <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header" id="kt_modal_add_user_header">
                                                <h2 class="fw-bolder">Nuevo Usuario</h2>
                                            </div>
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <form method="POST" action="{{ route('usuarios.store') }}"
                                                    id="kt_modal_add_user_form" class="form"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="d-flex flex-column scroll-y me-n7 pe-7"
                                                        id="kt_modal_add_user_scroll" data-kt-scroll="true"
                                                        data-kt-scroll-activate="{default: false, lg: true}"
                                                        data-kt-scroll-max-height="auto"
                                                        data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                                        data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                                                        data-kt-scroll-offset="300px">
                                                        <div class="fv-row mb-7">
                                                            <label class="required fw-bold fs-6 mb-2">Nombre</label>
                                                            <input type="text" name="name"
                                                                class="form-control form-control-solid mb-3 mb-lg-0"
                                                                placeholder="" value="{{ old('name') }}" />
                                                        </div>
                                                        <div class="fv-row mb-7">
                                                            <label class="required fw-bold fs-6 mb-2">Email</label>
                                                            <input type="email" name="email"
                                                                class="form-control form-control-solid mb-3 mb-lg-0"
                                                                placeholder="" value="{{ old('email') }}" />
                                                        </div>
                                                        <div class="fv-row mb-7">
                                                            <label class="required fw-bold fs-6 mb-2">Contraseña</label>
                                                            <input type="password" name="password"
                                                                class="form-control form-control-solid mb-3 mb-lg-0"
                                                                placeholder="" value="" />
                                                        </div>
                                                        <div class="fv-row mb-7">
                                                            <select name="roles" class="form-control form-control-solid"
                                                                data-control="select2"
                                                                data-placeholder="Seleccione una opción">
                                                                <option></option>
                                                                @foreach ($roles as $role)
                                                                    <option value="{{ $role }}">{{ $role }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="text-center pt-15">
                                                        <button type="button" class="btn btn-danger me-3"
                                                            data-bs-dismiss="modal">
                                                            Cancelar
                                                        </button>
                                                        <button id="kt_docs_formvalidation_submit" type="submit"
                                                            class="btn btn-primary">
                                                            <span class="indicator-label">Registrar</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Revisar los campos</strong>
                                    @foreach ($errors->all() as $error)
                                        <span class="badge bg-danger">{{ $error }}</span>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <table class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%"
                                id="registers">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">ID</th>
                                        <th class="min-w-125px">NOMBRE</th>
                                        <th class="min-w-125px">EMAIL</th>
                                        <th class="min-w-125px">ROL</th>
                                        <th class="min-w-125px">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold">
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if (!empty($user->getRoleNames()))
                                                    @foreach ($user->getRoleNames() as $rolName)
                                                        <h5><span class="badge badge-dark">{{ $rolName }}</span></h5>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="row">
                                                    <div class="col">
                                                        <div style="width: auto; height: auto;">
                                                            <a href="{{ route('usuarios.edit', ['usuario' => $user->id]) }}"
                                                                class="btn btn-warning"><i class="fas fa-pen"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div style="width: auto; height: auto;">
                                                            <form id="deleteForm"
                                                                action="{{ route('usuarios.destroy', ['usuario' => $user->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger delete-btn"><i
                                                                        class="fa-regular fa-trash-can"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
