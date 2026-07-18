# Pokemon Frontier

A web-based Pokémon tournament management system built with Laravel 12 and Oracle 21c XE as a CSE 3rd Year Database Course Project.

## Overview

Pokemon Frontier allows trainers to register, build Pokémon teams, and compete in tournaments. Match outcomes are determined automatically through a stat-based simulation algorithm implemented entirely in Oracle PL/SQL. The platform supports two user roles — Trainer and Admin — each with their own authentication and privileges.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Database:** Oracle Database 21c Express Edition
- **DB Driver:** yajra/laravel-oci8 v12
- **Stored Logic:** Oracle PL/SQL
- **Frontend:** Laravel Blade + Custom CSS
- **Web Server:** Apache via XAMPP

## Features

**Trainers can:**
- Register and log in
- Browse the Pokédex and filter by type
- Build teams of up to 6 Pokémon
- Register teams into open tournaments
- View match history and scores
- Track rankings on the leaderboard

**Admins can:**
- Add, edit, and delete Pokémon
- Create and manage leagues and tournaments
- Trigger match simulation rounds
- Monitor all registrations and results

## Database

10 Oracle tables: `POKEMON`, `TRAINER`, `ADMIN`, `TEAM`, `TEAM_POKEMON`, `LEAGUE`, `TOURNAMENT`, `REGISTRATION`, `MATCH_RESULT`, `RANKING`

### PL/SQL Components
- `get_team_power` — Function that calculates total team stat power
- `simulate_match` — Procedure that simulates a match and updates rankings
- `run_tournament_round` — Procedure that processes all pending matches in a tournament
- `create_tournament_matches` — Procedure that pairs up registered trainers into matches
- `trg_update_ranking` — Trigger that auto-updates rankings after each match
- `trg_team_size_check` — Trigger that enforces the 6 Pokémon per team limit

## Installation

### Requirements
- PHP 8.2
- XAMPP (Apache)
- Oracle Database 21c XE
- Oracle Instant Client 21.22
- Composer

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/yourusername/pokemon-frontier.git
cd pokemon-frontier
```

**2. Install dependencies**
```bash
composer install
```

**3. Copy environment file**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Configure `.env`**
```env
DB_CONNECTION=oracle
DB_HOST=localhost
DB_PORT=1521
DB_SERVICE_NAME=XEPDB1
DB_USERNAME=your_oracle_user
DB_PASSWORD=your_password
DB_CHARSET=AL32UTF8
DB_SERVER_VERSION=21c
```

**5. Set up Oracle**

Connect to SQL*Plus and create a dedicated user:
```sql
CREATE USER your_user IDENTIFIED BY your_password;
GRANT CONNECT, RESOURCE TO your_user;
GRANT UNLIMITED TABLESPACE TO your_user;
GRANT ALL PRIVILEGES TO your_user;
```

Run the schema file:
```sql
@database/sql/schema.sql
```

Run the seed data:
```sql
@database/sql/seed_pokemon.sql
```

**6. Run Laravel migrations**
```bash
php artisan migrate
```

**7. Seed admin account**

Generate a password hash in tinker:
```bash
php artisan tinker
echo Hash::make('your_admin_password');
```

Insert into Oracle:
```sql
INSERT INTO ADMIN (username, password_hash)
VALUES ('admin', 'your_generated_hash');
COMMIT;
```

**8. Start the server**
```bash
php artisan serve
```

Visit `http://localhost:8000`

## Usage

| URL | Description |
|---|---|
| `/` | Landing page |
| `/register` | Trainer registration |
| `/login` | Trainer login |
| `/pokedex` | Browse Pokémon |
| `/teams` | Build and manage teams |
| `/tournaments` | Browse and join tournaments |
| `/leaderboard` | View rankings |
| `/dashboard` | Trainer dashboard |
| `/admin/login` | Admin login |
| `/admin/pokemon` | Manage Pokédex |
| `/admin/leagues` | Manage leagues and tournaments |



## License

This project was built for educational purposes as a university database course project.
