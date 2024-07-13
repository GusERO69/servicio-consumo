@extends('template')

@section('section-template')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Revisar los campos</strong>
            @foreach ($errors->all() as $error)
                <span class="badge badge-danger">{{ $error }}</span>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Editar usuario</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="d-flex justify-content-between mt-5 mb-5">
                    <h1>Editar usuario</h1>
                </div>
                <div class="row g-7">
                    <div class="col-xl-12">
                        <div class="card card-flush h-lg-100" id="kt_contacts_main">
                            <div class="card-body pt-5">
                                <form class="form scroll-y" method="POST"
                                    action="{{ route('usuarios.update', ['usuario' => $user->id]) }}"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <span class="required">Nombre</span>
                                        </label>
                                        <input type="text" class="form-control form-control-solid" name="name"
                                            value="{{ $user->name }}" />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <span class="required">Rol</span>
                                        </label>
                                        <div class="form-floating border rounded">
                                            <select id="kt_ecommerce_select2_country"
                                                class="form-select form-select-solid lh-1 py-3" name="roles"
                                                data-kt-ecommerce-settings-type="select2_flags"
                                                data-placeholder="Seleccione rol" data-control="select2">
                                                @foreach ($roles as $roleValue => $roleLabel)
                                                    <option value="{{ $roleValue }}"
                                                        {{ in_array($roleValue, $userRole) ? 'selected' : '' }}>
                                                        {{ $roleLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="separator mb-6"></div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('usuarios.index', ['language' => app()->getLocale()]) }}"
                                            class="btn btn-bioactiva me-3">Cancelar</a>
                                        <button type="submit" class="btn btn-primary">
                                            <span class="indicator-label">Actualizar</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
