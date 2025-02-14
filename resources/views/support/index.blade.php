@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center py-4">
                <h1 class="mb-0">Support Technique</h1>
            </div>
            <div class="card-body p-5">
                <p class="lead text-center">Nous sommes là pour vous aider !</p>
                <p class="text-center">Contactez-nous pour toute assistance technique ou demande d'informations.</p>

                <div class="row text-center mt-4">
                    <!-- Email Section -->
                    <div class="col-md-6 mb-4">
                        <div class="p-4 border rounded shadow-sm">
                            <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                            <h5>Email</h5>
                            <p class="mb-0">support@hypotherapp.com</p>
                        </div>
                    </div>
                    <!-- Phone Section -->
                    <div class="col-md-6 mb-4">
                        <div class="p-4 border rounded shadow-sm">
                            <i class="fas fa-phone fa-3x text-success mb-3"></i>
                            <h5>Téléphone</h5>
                            <p class="mb-0">+32 XXXXXXXXX</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
