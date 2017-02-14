PRAGMA foreign_keys=ON;

BEGIN TRANSACTION;

CREATE TABLE horo_info (
  id                INTEGER PRIMARY KEY,
  hunger            REAL,
  hp                REAL,
  'level'           INTEGER,
  knowlege          INTEGER
);

CREATE TABLE activity_log (
  machine_id        TEXT,
  operation         TEXT,
  params            TEXT,
  created_at        TEXT,
  updated_at        TEXT
);

CREATE TABLE user_info (
  machine_id        TEXT UNIQUE,
  user_name         TEXT,
  horo_love_degree  REAL,
  food_contrib      REAL,
  knowlege_contrib  REAL,
  clean_contrib     REAL,
  pending_time      INTEGER,
  last_request_time INTEGER,
  created_at        TEXT,
  updated_at        TEXT
);

INSERT INTO horo_info (id, hunger, hp, 'level', knowlege)
VALUES (1, 50.0, 100.0, 0, 5000);

COMMIT;
