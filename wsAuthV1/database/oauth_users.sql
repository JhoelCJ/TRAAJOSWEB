ALTER TABLE users
    ADD COLUMN IF NOT EXISTS auth_provider VARCHAR(50),
    ADD COLUMN IF NOT EXISTS provider_id VARCHAR(191),
    ADD COLUMN IF NOT EXISTS avatar_url TEXT;

CREATE UNIQUE INDEX IF NOT EXISTS users_auth_provider_provider_id_unique
    ON users (auth_provider, provider_id)
    WHERE auth_provider IS NOT NULL AND provider_id IS NOT NULL;
