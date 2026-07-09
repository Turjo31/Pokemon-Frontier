-- ============================================================
--  Pokemon Frontier — Database Schema
--  Oracle Database 21c XE
--  Schema: TURJO / XEPDB1
-- ============================================================

-- ── POKEMON ──────────────────────────────────────────────────
CREATE TABLE POKEMON (
    pokemon_id   NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name         VARCHAR2(50)  NOT NULL,
    type1        VARCHAR2(20)  NOT NULL,
    type2        VARCHAR2(20),
    hp           NUMBER        NOT NULL,
    attack       NUMBER        NOT NULL,
    defense      NUMBER        NOT NULL,
    speed        NUMBER        NOT NULL,
    sprite_url   VARCHAR2(255)
);

-- ── ADMIN ────────────────────────────────────────────────────
CREATE TABLE ADMIN (
    admin_id      NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    username      VARCHAR2(50)  NOT NULL UNIQUE,
    password_hash VARCHAR2(255) NOT NULL
);

-- ── TRAINER ──────────────────────────────────────────────────
CREATE TABLE TRAINER (
    trainer_id    NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    username      VARCHAR2(50)  NOT NULL UNIQUE,
    email         VARCHAR2(100) NOT NULL UNIQUE,
    password_hash VARCHAR2(255) NOT NULL,
    rank_points   NUMBER        DEFAULT 0,
    joined_at     TIMESTAMP     DEFAULT SYSTIMESTAMP
);

-- ── TEAM ─────────────────────────────────────────────────────
CREATE TABLE TEAM (
    team_id    NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    trainer_id NUMBER        NOT NULL,
    team_name  VARCHAR2(50)  NOT NULL,
    created_at TIMESTAMP     DEFAULT SYSTIMESTAMP,
    CONSTRAINT fk_team_trainer
        FOREIGN KEY (trainer_id) REFERENCES TRAINER(trainer_id)
);

-- ── TEAM_POKEMON ─────────────────────────────────────────────
CREATE TABLE TEAM_POKEMON (
    team_pokemon_id NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    team_id         NUMBER    NOT NULL,
    pokemon_id      NUMBER    NOT NULL,
    slot            NUMBER(1) NOT NULL,
    CONSTRAINT fk_tp_team
        FOREIGN KEY (team_id)    REFERENCES TEAM(team_id),
    CONSTRAINT fk_tp_pokemon
        FOREIGN KEY (pokemon_id) REFERENCES POKEMON(pokemon_id),
    CONSTRAINT chk_slot_range
        CHECK (slot BETWEEN 1 AND 6)
);

-- ── LEAGUE ───────────────────────────────────────────────────
CREATE TABLE LEAGUE (
    league_id  NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    created_by NUMBER        NOT NULL,
    name       VARCHAR2(100) NOT NULL,
    start_date DATE,
    end_date   DATE,
    status     VARCHAR2(20)  DEFAULT 'upcoming',
    CONSTRAINT fk_league_admin
        FOREIGN KEY (created_by) REFERENCES ADMIN(admin_id)
);

-- ── TOURNAMENT ───────────────────────────────────────────────
CREATE TABLE TOURNAMENT (
    tournament_id    NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    league_id        NUMBER        NOT NULL,
    name             VARCHAR2(100) NOT NULL,
    max_participants NUMBER        DEFAULT 16,
    bracket_type     VARCHAR2(20)  DEFAULT 'single_elimination',
    status           VARCHAR2(20)  DEFAULT 'open',
    CONSTRAINT fk_tournament_league
        FOREIGN KEY (league_id) REFERENCES LEAGUE(league_id)
);

-- ── REGISTRATION ─────────────────────────────────────────────
CREATE TABLE REGISTRATION (
    reg_id        NUMBER    GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    trainer_id    NUMBER    NOT NULL,
    tournament_id NUMBER    NOT NULL,
    team_id       NUMBER    NOT NULL,
    registered_at TIMESTAMP DEFAULT SYSTIMESTAMP,
    CONSTRAINT fk_reg_trainer
        FOREIGN KEY (trainer_id)    REFERENCES TRAINER(trainer_id),
    CONSTRAINT fk_reg_tournament
        FOREIGN KEY (tournament_id) REFERENCES TOURNAMENT(tournament_id),
    CONSTRAINT fk_reg_team
        FOREIGN KEY (team_id)       REFERENCES TEAM(team_id),
    CONSTRAINT uq_reg_unique
        UNIQUE (trainer_id, tournament_id)
);

-- ── MATCH_RESULT ─────────────────────────────────────────────
CREATE TABLE MATCH_RESULT (
    match_id      NUMBER    GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    tournament_id NUMBER    NOT NULL,
    trainer1_id   NUMBER    NOT NULL,
    trainer2_id   NUMBER    NOT NULL,
    team1_id      NUMBER    NOT NULL,
    team2_id      NUMBER    NOT NULL,
    winner_id     NUMBER    NOT NULL,
    team1_score   NUMBER,
    team2_score   NUMBER,
    match_date    TIMESTAMP DEFAULT SYSTIMESTAMP,
    CONSTRAINT fk_match_tournament
        FOREIGN KEY (tournament_id) REFERENCES TOURNAMENT(tournament_id),
    CONSTRAINT fk_match_trainer1
        FOREIGN KEY (trainer1_id)   REFERENCES TRAINER(trainer_id),
    CONSTRAINT fk_match_trainer2
        FOREIGN KEY (trainer2_id)   REFERENCES TRAINER(trainer_id),
    CONSTRAINT fk_match_team1
        FOREIGN KEY (team1_id)      REFERENCES TEAM(team_id),
    CONSTRAINT fk_match_team2
        FOREIGN KEY (team2_id)      REFERENCES TEAM(team_id),
    CONSTRAINT fk_match_winner
        FOREIGN KEY (winner_id)     REFERENCES TRAINER(trainer_id)
);

-- ── RANKING ──────────────────────────────────────────────────
CREATE TABLE RANKING (
    ranking_id   NUMBER    GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    trainer_id   NUMBER    NOT NULL,
    league_id    NUMBER    NOT NULL,
    points       NUMBER    DEFAULT 0,
    wins         NUMBER    DEFAULT 0,
    losses       NUMBER    DEFAULT 0,
    last_updated TIMESTAMP DEFAULT SYSTIMESTAMP,
    CONSTRAINT fk_ranking_trainer
        FOREIGN KEY (trainer_id) REFERENCES TRAINER(trainer_id),
    CONSTRAINT fk_ranking_league
        FOREIGN KEY (league_id)  REFERENCES LEAGUE(league_id),
    CONSTRAINT uq_ranking_unique
        UNIQUE (trainer_id, league_id)
);