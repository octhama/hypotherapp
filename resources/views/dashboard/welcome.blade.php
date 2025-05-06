<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hypotherapp - Welcome Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--light-color);
            font-family: var(--font-family);
            color: var(--dark-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }

        .container {
            max-width: 1200px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            padding: 20px;
        }

        .menu-card {
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
        }

        .menu-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .menu-card i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .user-menu {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .user-menu .dropdown-toggle {
            padding: 5px 10px;
        }

        .user-menu .dropdown-menu {
            right: 0;
            left: auto;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .container {
                margin-top: 60px;
            }
            .row {
                flex-direction: column;
            }
            .menu-card {
                width: 100%;
                margin: 10px 0;
            }
            .menu-card i {
                font-size: 2rem;
            }
            .menu-card h5 {
                font-size: 1.2rem;
            }
            .menu-card .btn {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
            .user-menu {
                top: 10px;
                right: 10px;
            }
            .user-menu .dropdown-toggle {
                font-size: 0.9rem;
            }
            .container h1 {
                font-size: 1.5rem;
            }
            .container h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Afficher les messages d'alerte -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Inclure le composant d'alerte -->
    @include('components.alert')
    <div class="user-menu">
        <li class="nav-item dropdown list-unstyled">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                @php
                    $defaultAdminAvatar = 'https://github.com/mdo.png';
                    $defaultEmployeeAvatar = asset('images/employee_two.png');
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
    </div>
    <h1>Bienvenue sur Hypotherapp</h1>
    <h2 class="mb-4">Navigation principale</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card menu-card text-center">
                <div class="card-body">
                    <i class="fas fa-users text-primary"></i>
                    <h5 class="card-title">Gestion des Clients</h5>
                    <p>Gestion des clients et des rendez-vous.</p>
                    <a href="{{ route('clients.index') }}" class="btn btn-primary">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card menu-card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-alt text-success"></i>
                    <h5 class="card-title">Gestion des Rendez-vous</h5>
                    <p>Planifiez les rendez-vous.</p>
                    <a href="{{ route('rendez-vous.index') }}" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card menu-card text-center">
                <div class="card-body">
                    <i class="fas fa-horse text-warning"></i>
                    <h5 class="card-title">Gestion des Poneys</h5>
                    <p>Enregistrez les poneys.</p>
                    <a href="{{ route('poneys.index') }}" class="btn btn-warning">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card menu-card text-center @if(auth()->user()->isEmployee()) bg-light text-muted @endif">
                <div class="card-body">
                    <i class="fas fa-chart-line text-info"></i>
                    <h5 class="card-title">Rapports et Statistiques</h5>
                    <p>Analysez vos données.</p>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('rapports.index') }}" class="btn btn-info">Accéder</a>
                    @else
                        <button class="btn btn-secondary" disabled>Accès restreint</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card menu-card text-center">
                <div class="card-body">
                    <i class="fas fa-cogs text-secondary"></i>
                    <h5 class="card-title">Paramètres</h5>
                    <p>Configurez votre application.</p>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card menu-card text-center">
                <div class="card-body">
                    <i class="fas fa-life-ring text-danger"></i>
                    <h5 class="card-title">Support</h5>
                    <p>Assistance technique.</p>
                    <a href="{{ route('support.index') }}" class="btn btn-danger">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
