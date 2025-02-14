<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>@yield('title', 'Hypotherapp - Gestion des Poneys')</title>
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-radius: 12px;
            width: 90%;
            max-width: 1200px;
            margin: 1rem auto;
        }
        .navbar .nav-link {
            color: #2c3e50 !important;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.2s ease;
        }
        .navbar .nav-link:hover {
            color: #6c5ce7 !important;
            transform: translateY(-2px);
        }
        .navbar-brand {
            color: #6c5ce7 !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .container {
            flex: 1;
            padding: 2rem 0;
            animation: fadeIn 0.8s ease-out;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            border: none;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        footer {
            background: #ffffff;
            color: #2c3e50;
            padding: 1.5rem;
            border-radius: 12px;
            width: 90%;
            max-width: 1200px;
            margin: 1rem auto;
            text-align: center;
        }
        footer a {
            color: #6c5ce7;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        footer a:hover {
            color: #5a4acf;
        }

        @media (max-width: 768px) {
            .navbar, .container, footer {
                width: 95%;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hypotherapp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.welcome') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('rendez-vous.index') }}">Rendez-vous</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="clientsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Clients</a>
                    <ul class="dropdown-menu" aria-labelledby="clientsDropdown">
                        <li><a class="dropdown-item" href="{{ route('clients.index') }}"><i class="fas fa-list"></i> Liste des clients</a></li>
                        <li><a class="dropdown-item" href="{{ route('facturation.index') }}"><i class="fas fa-file-invoice"></i> Historique des facturations</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('poneys.index') }}">Poneys</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        @php
                            $defaultAdminAvatar = 'https://github.com/mdo.png';
                            $defaultEmployeeAvatar = asset('images/employee_two.png'); // Chemin vers votre avatar par défaut
                            $avatar = Auth::check() ? (Auth::user()->isAdmin() ? $defaultAdminAvatar : $defaultEmployeeAvatar) : $defaultEmployeeAvatar;
                        @endphp

                        <img src="{{ Auth::check() ? (Auth::user()->avatar ?? $avatar) : $avatar }}" class="rounded-circle me-2" width="25" height="25" alt="Avatar">
                        {{ Auth::check() ? Auth::user()->name : 'Invité' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if (Auth::check())
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-circle me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i> Paramètres</a></li>
                            <li><a class="dropdown-item" href="{{ route('support.index') }}"><i class="fas fa-life-ring me-2"></i> Support</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i> Connexion</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</div>

<footer>
    <p class="mb-2">&copy; 2025 Hypotherapp - Gestion des Poneys. Tous droits réservés.</p>
    <p class="mb-0"><a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
