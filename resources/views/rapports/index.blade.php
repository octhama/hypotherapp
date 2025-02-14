@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Rapports & Statistiques</h1>

        <!-- Conteneur avec 3 cartes de taille identique -->
        <div class="row">
            <!-- Carte 1 : Heures Réservées par Client -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 chart-card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Minutes Réservées par Client</h5>
                        <div class="chart-container">
                            <canvas id="clientsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 2 : Tendance des Factures -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 chart-card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Tendance des Factures</h5>
                        <div class="chart-container">
                            <canvas id="facturesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 3 : Heures de Travail Validées par Poney -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 chart-card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Heures de Travail Validées par Poney</h5>
                        <div class="chart-container">
                            <canvas id="poneysChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles pour uniformiser les cartes et les graphiques -->
    <style>
        .chart-card {
            height: 400px; /* Hauteur fixe pour uniformiser */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-container {
            width: 100%;
            height: 300px; /* Taille identique pour tous les charts */
            position: relative;
        }
    </style>

    <!-- Scripts pour Chart.js -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Configuration commune des graphiques
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            };

            // Graphique Clients (Bar Chart)
            new Chart(document.getElementById('clientsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($clientsLabels) !!},
                    datasets: [{
                        label: 'Heures réservées',
                        data: {!! json_encode($clientsData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: commonOptions
            });

            // Graphique Factures (Line Chart)
            new Chart(document.getElementById('facturesChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($factureLabels) !!},
                    datasets: [{
                        label: 'Montant (€)',
                        data: {!! json_encode($factureData) !!},
                        borderColor: '#007bff',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        x: {
                            ticks: { autoSkip: true, maxTicksLimit: 7 },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: false,
                            ticks: { callback: value => value + " €" },
                            grid: { color: "rgba(200, 200, 200, 0.2)" }
                        }
                    }
                }
            });

            // Graphique Poneys (Bar Chart)
            new Chart(document.getElementById('poneysChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($poneyLabels) !!},
                    datasets: [{
                        label: 'Heures de travail',
                        data: {!! json_encode($poneyData) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: commonOptions
            });
        });
    </script>
@endsection
