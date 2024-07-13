@extends('template')

@section('section-template')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-xl-12">
                    <div class="card h-xl-100">
                        <div class="card-body">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%" id="registers">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">FECHA</th>
                                        <th class="min-w-125px">VALOR_REAL</th>
                                        <th class="min-w-125px">IA</th>
                                        {{-- <th class="min-w-125px">PROBABILIDAD</th> --}}
                                        {{-- <th class="min-w-125px">FECHA</th>
                                        <th class="min-w-125px">ERROR</th> --}}
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold">
                                    {{-- @foreach ($reading as $read)
                                        <tr>
                                            <td>{{ $read->id }}</td>
                                            <td>{{ $read->user_id }}</td>
                                            <td>{{ $read->voltage }}</td>
                                            <td>{{ $read->current }}</td>
                                            <td>{{ $read->power }}</td>
                                            <td>{{ $read->timestamp }}</td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-xl-12">
                    <div class="card h-xl-100">
                        <div class="card-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card h-xl-100">
                        <div class="card-body">
                            <div id="tablaPrediccion"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-md-4">
                    <form id="formPredecir" method="POST" action="{{ route('dashboard.store') }}">
                        @csrf
                        <button type="submit" class="btn btn-success">Predecir</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <span id="resultadoPrediccion"></span>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#formPredecir').submit(function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);

                    // if (response.prediction !== undefined) {
                    //     $('#resultadoPrediccion').text('Predicción: ' + response
                    //         .prediction + ' W');
                    // } else {
                    //     $('#resultadoPrediccion').text('Error al obtener la predicción.');
                    // }

                    var pred = response['prediction']

                    var predictionData = pred.predictions.map(function(item) {
                        console.log("predictionData", item.date);
                        return {
                            x: item.date,
                            y: item.prediction.toFixed(2)
                        };
                    });

                    var realData = pred.predictions.map(function(item) {
                        // console.log("realData", item.date);
                        return {
                            x: item.date,
                            y: item.real ? item.real.toFixed(2) : ''
                        };
                    });

                    var options = {
                        series: [{
                                name: 'Predicción',
                                data: predictionData
                            },
                            {
                                name: 'Real',
                                data: realData
                            }
                        ],
                        chart: {
                            type: 'bar',
                            height: 350,
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded',
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            type: 'datetime',
                            categories: predictionData.map(function(item) {
                                return item.x;
                            })
                        },
                        yaxis: {
                            title: {
                                text: 'Watts?'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            x: {
                                format: 'yyyy/MM/dd HH:mm'
                            },
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(2) +
                                        " kW";
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

                    var sumasPorFecha = response
                        .sumasPorFecha;

                    var tableHtml =
                        '<table class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%"><thead><tr><th>Fecha</th><th>Predicción</th></tr></thead><tbody>';

                    Object.keys(sumasPorFecha).forEach(function(date) {
                        var sumaAjustada = sumasPorFecha[date].toFixed(2);

                        tableHtml += '<tr><td>' + date + '</td><td>' +
                            sumaAjustada + '</td></tr>';
                    });

                    tableHtml += '</tbody></table>';

                    $('#tablaPrediccion').html(tableHtml);
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener la predicción:', error);
                    $('#resultadoPrediccion').text('Error al obtener la predicción.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#registers').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getData') }}',
            dataType: 'json',
            type: 'POST',
            columns: [{
                    data: 'date_week',
                    name: 'date_week'
                },
                {
                    data: 'real_data',
                    name: 'real_data',
                },
                {
                    data: 'prediction',
                    name: 'prediction',
                },
                // {
                //     data: 'probability',
                //     name: 'probability'
                // },
                // {
                //     data: 'date_week',
                //     name: 'date_week',
                // },
                // {
                //     data: 'error',
                //     name: 'error',
                // }
            ],
        });
    });
</script>
