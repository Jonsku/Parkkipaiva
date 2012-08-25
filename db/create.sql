CREATE TABLE owners (
id INTEGER PRIMARY KEY AUTOINCREMENT,
fb_id INTEGER,
email_id TEXT,
name TEXT,
phone TEXT,
password TEXT,
status INTEGER,
timestamp TEXT
);

CREATE TABLE stands (
id INTEGER PRIMARY KEY AUTOINCREMENT,
owner INTEGER,
address TEXT,
u REAL,
v REAL,
start_hour INTEGER,
start_minute INTEGER,
end_hour INTEGER,
end_minute INTEGER,
description BLOB,
slots  INTEGER,
timestamp TEXT,
modified TEXT
);
CREATE INDEX owners_of_stands ON stands (owner);
CREATE UNIQUE INDEX stands_owner ON stands (id, owner);

