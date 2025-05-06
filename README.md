# Hypotherapp - Gestion des Poneys 🐴🗓️💰

Hypotherapp est une application web développée avec Laravel pour gérer les **rendez-vous**, les **clients**, les **poneys** et surtout la **facturation**. Elle permet aux utilisateurs de **planifier des rendez-vous**, **assigner des poneys à des clients** et **générer des factures PDF** automatiquement.

## 🌟 Contexte

Ce projet a été réalisé dans le cadre de la formation **Bachelier en Informatique - orientation développement d'applications ** dispensée par **ESA Namur**. Il s'agit du projet final du cours de **Développement Web Dynamique** qui a pour objectif de prendre en main le framework **Laravel** et de développer une application web complète.

## 📝 Description

L'application est conçue pour un centre équestre qui propose des balades à poney pour les enfants. Les clients peuvent réserver des créneaux horaires pour une durée de 10 à 20 minutes et choisir le nombre de poneys pour leur balade. Les employés peuvent gérer les rendez-vous, les clients et les poneys, ainsi que générer des factures pour les clients.

## 🎯 Objectifs

- **Gestion des rendez-vous** : Planifier des rendez-vous avec des créneaux horaires disponibles.
- **Gestion des clients** : Ajouter, modifier et supprimer des clients.
- **Gestion des poneys** : Assigner des poneys à des rendez-vous, ajouter de nouveaux poneys, modifier et supprimer des poneys.
- **Facturation** : Générer des factures PDF détaillées pour les clients.
- **Authentification** : Inscription, connexion et gestion des utilisateurs avec des rôles (admin, employee).
- **Interface utilisateur** : Conception d'une interface intuitive et conviviale pour une navigation fluide.

## 🚀 Fonctionnalités principales

- **🔒 Authentification** : Inscription, connexion et gestion des utilisateurs avec des rôles (admin, employee).
- **🤝 Gestion des clients** : Ajouter, modifier et supprimer des clients.
- **🐴 Gestion des poneys** : Assigner des poneys à des rendez-vous, ajouter de nouveaux poneys, modifier et supprimer des poneys.
- **🗓️ Rendez-vous** : Planifier des rendez-vous avec des créneaux horaires disponibles.
- **💰 Facturation (PDF)** : Générer des factures PDF détaillées grâce à **Laravel DomPDF**.
- **🎨 Interface utilisateur intuitive** : Conçue pour une navigation fluide et agréable.

## 🛠️ Technologies utilisées

- **Backend** : Laravel 10.x
- **Frontend** : Bootstrap 5, Font Awesome, Tailwind CSS
- **Template Engine** : Blade
- **Base de données** : SQLite
- **Génération de factures PDF** : `barryvdh/laravel-dompdf`
- **Autres outils** : Composer, npm, Carbon (gestion des dates)

## 📦 Installation

Suivez ces étapes pour installer et configurer le projet localement.

### ⚙️ Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js et npm (pour les assets frontend)

### 🔧 Étapes d'installation

#### 1️⃣ Cloner le dépôt

```bash
git clone https://github.com/octhama/SandboxWebDynPHP.git
cd SandboxWebDynPHP/hypotherapp
```

#### 2️⃣ Installer les dépendances PHP

```bash
composer install
```

#### 3️⃣ Installer les dépendances JavaScript

```bash
npm install
npm run build
```

#### 4️⃣ Configurer l'environnement

Copiez le fichier `.env.example` en `.env` :

```bash
cp .env.example .env
```

Vérifiez la configuration de la base de données dans le fichier `.env` :

```env
DB_CONNECTION=sqlite
DB_DATABASE=../database/database.sqlite # Chemin vers la base de données SQLite
# DB_DATABASE="database/database.sqlite"  Chemin vers la base de données SQLite pour effectuer les migrations et les seeders
```

#### 5️⃣ Générer une clé d'application

```bash
php artisan key:generate
```

#### 6️⃣ Exécuter les migrations et les seeders

```bash
php artisan migrate --seed
```

#### 7️⃣ Installer Laravel DomPDF pour la facturation

Le package **`barryvdh/laravel-dompdf`** est utilisé pour générer des factures au format PDF. Installez-le avec :

```bash
composer require barryvdh/laravel-dompdf
```

#### 8️⃣ Démarrer le serveur de développement

```bash
php artisan serve
```

L'application sera accessible à l'adresse : **[http://127.0.0.1:8000](http://localhost:8000)**

## Utilisation de l'ORM (Eloquent) dans Hypotherapp

Hypotherapp utilise **Eloquent**, l'ORM de Laravel, pour la gestion des données.

### Exemple : Modèle `Client`

Un client est lié à une facturation par une relation **un-à-un** :
```php
class Client extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'nombre_personnes', 'minutes', 'prix_total'];

    public function facturation(): HasOne
    {
        return $this->hasOne(Facturation::class);
    }
}
```

### Exemple : Facturation automatique

Lorsqu'un client est créé, une facturation lui est automatiquement associée :
```php
protected static function boot(): void
{
    parent::boot();
    static::created(function ($client) {
        (new Facturation)->create([
            'client_id' => $client->id,
            'nombre_minutes' => $client->minutes,
            'montant' => $client->prix_total,
        ]);
    });
}
```

### Relations Eloquent utilisées dans le projet

- **Client - Facturation** : `HasOne`
- **Client - Rendez-vous** : `HasMany`
- **Rendez-vous - Poney** : `BelongsToMany`

### Exemple : Relation `RendezVous - Poney`
```php
public function poneys(): BelongsToMany
{
    return $this->belongsToMany(Poney::class, 'rendez_vous_poneys', 'rendez_vous_id', 'poney_id');
}
```

## Structure de la base de données

La base de données SQLite contient les tables suivantes :

- **users** : Gère les utilisateurs et leurs rôles
- **clients** : Stocke les informations des clients
- **poneys** : Liste les poneys et leur disponibilité
- **rendez_vous** : Gère les rendez-vous avec les clients
- **facturations** : Stocke les factures générées
- **rendez_vous_poneys** : Associe les poneys aux rendez-vous
- **sessions** : Stocke les sessions des utilisateurs

## Gestion des accès et permissions

### Middleware

- **AdminMiddleware** : Restreint l'accès aux administrateurs uniquement.
- **Authenticate** : Redirige les utilisateurs non authentifiés vers la page de connexion.
- **RestrictEmployeeAccess** : Empêche les employés d'accéder à certaines sections comme les paramètres.

### Policies

- **PoneyPolicy** :
    - Les employés peuvent voir les poneys mais seuls les administrateurs peuvent les gérer.

- **ClientPolicy** :
    - Les employés et administrateurs peuvent voir les clients.
    - Seuls les administrateurs peuvent modifier ou supprimer un client.
    - Les employés et administrateurs peuvent générer des factures.

## 📂 Structure du projet

```
📦 hypotherapp
├── app
│   ├── Http
│   │   ├── Controllers       # Contrôleurs (Clients, Poneys, Rendez-vous...)
│   │   └── Middleware        # Middleware (authentification, rôles)
│   ├── Models                # Modèles (Client, Facturation, Poney, RendezVous...)
│   ├── Policies              # Politiques d'accès (Clients, Poneys, Rendez-vous...)
│   └── Providers             # Fournisseurs de services (Auth, Route...)
├── config                    # Configuration de l'application
├── database
│   ├── factories             # Factories pour les tests
│   ├── migrations            # Migrations de la base de données
│   └── seeders               # Seeders pour peupler la base de données
├── public                    # Fichiers accessibles publiquement (images, CSS, JS...)
├── resources
│   ├── css                   # Fichiers CSS
│   ├── js                    # Fichiers JavaScript
│   └── views                 # Vues de l'application
│       ├── auth              # Vues d'authentification
│       ├── clients           # Vues des clients
│       ├── components        # Composants réutilisables
│       ├── dashboard         # Tableau de bord
│       ├── facturation       # Vues de facturation
│       ├── poneys            # Vues des poneys
│       └── rendez-vous       # Vues des rendez-vous
├── routes                    # Définition des routes
├── storage                   # Stockage (logs, cache, fichiers uploadés...)
├── tests                    # Tests (Unitaires et Feature)
└── vendor                    # Dépendances Composer (packages tiers)
```

## 📌 Utilisation

### 🗓️ 1️⃣ Créer un rendez-vous

1. Connectez-vous à l'application.
2. Accédez à la section **Rendez-vous**.
3. Ajoutez un client, le nombre de poneys et le nombre de minutes (min 10 min, max 20 min).
4. Accédez à la section **Clients - Liste des clients** pour voir la liste des clients avec la possibilité de les modifier ou les supprimer et voir les factures et les générer.
5. Accédez à la section **Nouveau Rendez-vous** pour ajouter un nouveau rendez-vous avec un client, le nombre de poneys et le créneau horaire désiré pour utiliser les minutes.
6. Cliquez sur **Créer le rendez-vous**.
7. Le rendez-vous sera ajouté avec succès dans la liste des rendez-vous.

### 💰 2️⃣ Générer une facture PDF

1. Accédez à la section **Clients - Liste des clients**.
2. Cliquer sur le bouton **Voir** pour voir les détails du client et générer la facture.
3. Cliquez sur **Générer la facture PDF**.
4. Le fichier **PDF** sera téléchargé automatiquement.

### 🐴 3️⃣ Gérer les poneys

1. Accédez à la section **Poneys**.
2. Ajoutez un nouveau poney avec le formulaire.
3. Cliquer sur **Ajouter le poney**.
4. Le poney sera ajouté avec succès dans la liste des poneys.

### 👤 4️⃣ Créer un utilisateur

1. Accédez à l'application via ce lien : **[http://127.0.0.1:8000](http://localhost:8000)**.
2. Si vous n'avez pas de compte, cliquez sur **S'inscrire**.
3. Remplissez le formulaire d'inscription.
4. Choisissez un rôle : **admin** ou **employee**.
5. Cliquez sur **S'inscrire**.
6. Vous serez redirigé vers la page de connexion.
7. Connectez-vous avec vos identifiants.
8. Cliquez sur **Se connecter**.

## ✨ Auteur

**Octhama** - Développeur principal - [GitHub](https://github.com/octhama)

## ❤️ Remerciements

Merci à l'équipe de Laravel pour leur excellent framework, ainsi qu'à la communauté open-source pour les outils utilisés dans ce projet.
Merci à **maitrepylos** - Php Sensei 🫡 - [GitHub](https://github.com/maitrepylos) pour ses précieux conseils et son soutien.

