import session from "express-session";

const lifetimeSeconds = Number(process.env.SESSION_LIFETIME_SECONDS ?? "60");
const isProduction = process.env.NODE_ENV === "production";

export const sessionMiddleware = session({
  name: "biconoir_session",
  secret: process.env.SESSION_SECRET ?? "change-this-session-secret",
  resave: false,
  saveUninitialized: false,
  cookie: {
    httpOnly: true,
    secure: isProduction,
    sameSite: "lax",
    maxAge: Math.max(1, lifetimeSeconds) * 1000
  }
});

declare module "express-session" {
  interface SessionData {
    user?: {
      userId: string;
      name: string;
      email: string;
      role: string;
    };
    oauthState?: string;
  }
}
