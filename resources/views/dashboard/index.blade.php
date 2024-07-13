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

            // Realizar la solicitud HTTP a Laravel
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(), // Envía los datos del formulario
                success: function(response) {
                    console.log(response); // Verifica la respuesta en la consola

                    // Manejar la respuesta JSON
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
                            type: 'datetime', // Si `item.date` es una fecha
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
                                format: 'yyyy/MM/dd HH:mm' // Formato de fecha para el tooltip, ajustar según sea necesario
                            },
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(2) +
                                        " kW"; // Formato personalizado para el tooltip
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

                    var sumasPorFecha = response
                        .sumasPorFecha; // Suponiendo que `response.tabla` contiene los datos de la tabla

                    // var test = response.sumasPorFecha;

                    // console.log("TEST", test);

                    var tableHtml =
                        '<table class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%"><thead><tr><th>Fecha</th><th>Predicción</th></tr></thead><tbody>';

                    Object.keys(sumasPorFecha).forEach(function(date) {
                        var sumaAjustada = sumasPorFecha[date].toFixed(2);

                        tableHtml += '<tr><td>' + date + '</td><td>' +
                            sumaAjustada + '</td></tr>';
                    });

                    tableHtml += '</tbody></table>';

                    // Insertar la tabla en el DOM
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
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                    name: 'Lectura',
                    data: @json($data)
                },
                {
                    name: 'Revenue',
                    data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
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
                categories: @json($reading->pluck('timestamp'))
            },
            yaxis: {
                title: {
                    text: '$ (thousands)'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toFixed(2) + " kW"; // Formato personalizado para el tooltip
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
</script> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos iniciales del gráfico
        var initialData = @json($data); // Datos iniciales que ya tienes

        var options = {
            series: [{
                name: 'Lectura',
                data: initialData
            }],
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
                categories: @json($reading->pluck('timestamp'))
            },
            yaxis: {
                title: {
                    text: '$ (thousands)'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toFixed(2) + " kW";
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#resultadoPrediccion"), options);
        chart.render();

        // Manejar el formulario de predicción y actualizar el gráfico con los datos de predicción
        $('#formPredecir').submit(function(event) {
            event.preventDefault();

            // Realizar la solicitud HTTP a Laravel
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response); // Verifica la respuesta en la consola

                    // Procesar los datos recibidos para ApexCharts
                    var pred = response['prediction']
                    console.log("pred", pred['predictions']);
                    var predictionData = pred.predictions.map(function(item) {
                        console.log("predictionData", item.date);
                        return {
                            x: item.date,
                            y: item.prediction.toFixed(2)
                        };
                    });

                    // Actualizar los datos en el gráfico de ApexCharts
                    chart.updateSeries([{
                        name: 'Predicción',
                        data: predictionData
                    }]);
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener la predicción:', error);
                    $('#resultadoPrediccion').text('Error al obtener la predicción.');
                }
            });
        });
    });
</script> --}}
