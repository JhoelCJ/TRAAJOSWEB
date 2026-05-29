import { prisma } from "./prisma.js";

let databaseReady: Promise<void> | null = null;

export function ensureDatabase(): Promise<void> {
  databaseReady ??= prisma.$executeRawUnsafe(`
    CREATE TABLE IF NOT EXISTS users (
      user_id VARCHAR(50) PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      phone VARCHAR(50),
      password_hash TEXT,
      role VARCHAR(50) NOT NULL DEFAULT 'customer'
    );
  `).then(() => undefined);

  return databaseReady;
}
