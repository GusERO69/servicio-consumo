<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Results;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $reading = Lectura::take(5)->get();
        $data = $reading->pluck('power')->toArray();
        return view('dashboard.index', compact('reading', 'data', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();

        try {
            $response = Http::timeout(120)->get('http://127.0.0.1:5000/predict/' . $userId);

            if ($response->successful()) {
                $prediction = $response->json();
                $results = Results::all();

                $sumasPorFecha = [];

                // Iterar sobre los resultados para sumar las predicciones por fecha
                foreach ($results as $result) {
                    // Calcular el valor ajustado antes de sumar
                    $valorAjustado = ($result->prediction / 1000) * 0.42;

                    // Obtener la fecha de forma solo de fecha sin la hora
                    $fecha = Carbon::parse($result->date_week)->toDateString(); // Ajusta según el formato de tu fecha

                    if (!isset($sumasPorFecha[$fecha])) {
                        $sumasPorFecha[$fecha] = 0;
                    }

                    // Sumar el valor ajustado
                    $sumasPorFecha[$fecha] += $valorAjustado;
                }

                return response()->json([
                    'prediction' => $prediction,
                    'results' => $results,
                    'sumasPorFecha' => $sumasPorFecha
                ]);
            } else {
                return response()->json([
                    'error' => 'Error al obtener la predicción desde Flask.'
                ], 500);
            }
        } catch (ConnectionException $e) {
            return response()->json([
                'error' => 'Timeout al conectar con Flask.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
