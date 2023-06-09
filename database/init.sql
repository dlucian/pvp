-- Main table holding all the files in the storage/vault
CREATE TABLE files (
    id SERIAL PRIMARY KEY,
    hash VARCHAR(64) NOT NULL,
    path TEXT UNIQUE NOT NULL,
    filename TEXT,
    filesize BIGINT,
    mime TEXT,
    date_created TIMESTAMP,
    gps_lat REAL,
    gps_lon REAL,
    gps_alt REAL,
    metadata JSONB DEFAULT NULL,
    transcript TEXT,
    scan_version INTEGER,
    scanned_at TIMESTAMP,
    analyzed_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    removed_at TIMESTAMP
);

-- Table holding all the issues found in the files
CREATE TABLE file_issues (
    id SERIAL PRIMARY KEY,
    hash VARCHAR(64) NOT NULL,
    path TEXT NOT NULL,
    filename TEXT,
    filesize BIGINT,
    tool VARCHAR(64) NOT NULL,
    issue TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
