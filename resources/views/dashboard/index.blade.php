@extends('template')

@section('section-template')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-xl-8">
                    <div class="card h-xl-100">
                        <div class="card-body">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%" id="registers">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">ID</th>
                                        <th class="min-w-125px">ID_USUARIO</th>
                                        <th class="min-w-125px">VOLTAJE</th>
                                        <th class="min-w-125px">CORRIENTE</th>
                                        <th class="min-w-125px">POTENCIA(W)</th>
                                        <th class="min-w-125px">REGISTRO</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold">
                                    @foreach ($reading as $read)
                                        <tr>
                                            <td>{{ $read->id }}</td>
                                            <td>{{ $read->user_id }}</td>
                                            <td>{{ $read->voltage }}</td>
                                            <td>{{ $read->current }}</td>
                                            <td>{{ $read->power }}</td>
                                            <td>{{ $read->timestamp }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card bg-primary h-xl-100">
                        <div class="card-body d-flex flex-column pt-13 pb-14">
                            <div class="m-0">
                                <h1 class="fw-bold text-white text-center lh-lg mb-9">How are you
                                    feeling today?
                                    <span class="fw-boldest">Here we are to Help</span>
                                </h1>
                                <div class="flex-grow-1 bgi-no-repeat bgi-size-contain bgi-position-x-center card-rounded-bottom h-200px mh-200px my-5 mb-lg-12"
                                    style="background-image:url('assets/media/svg/illustrations/easy/6.svg')">
                                </div>
                            </div>
                            <div class="text-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
