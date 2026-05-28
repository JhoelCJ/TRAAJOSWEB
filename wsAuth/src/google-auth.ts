import crypto from "node:crypto";
import { prisma } from "./prisma.js";

type GoogleTokenResponse = {
  access_token?: string;
  error_description?: string;
};

type GoogleProfile = {
  sub: string;
  name?: string;
  email?: string;
  email_verified?: boolean;
};

export function buildGoogleAuthorizationUrl(state: string): string {
  assertGoogleConfig();

  const params = new URLSearchParams({
    client_id: process.env.GOOGLE_CLIENT_ID!,
    redirect_uri: googleRedirectUri(),
    response_type: "code",
    scope: "openid email profile",
    state,
    prompt: "select_account"
  });

  return `https://accounts.google.com/o/oauth2/v2/auth?${params.toString()}`;
}

export async function signInWithGoogle(code: string) {
  assertGoogleConfig();

  const tokenResponse = await fetch("https://oauth2.googleapis.com/token", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      client_id: process.env.GOOGLE_CLIENT_ID!,
      client_secret: process.env.GOOGLE_CLIENT_SECRET!,
      redirect_uri: googleRedirectUri(),
      grant_type: "authorization_code",
      code
    })
  });

  const tokenData = (await tokenResponse.json()) as GoogleTokenResponse;
  if (!tokenResponse.ok || !tokenData.access_token) {
    throw new Error(tokenData.error_description ?? "Google did not return an access token.");
  }

  const profileResponse = await fetch("https://www.googleapis.com/oauth2/v3/userinfo", {
    headers: { Authorization: `Bearer ${tokenData.access_token}` }
  });
  const profile = (await profileResponse.json()) as GoogleProfile;

  if (!profileResponse.ok || !profile.email || !profile.email_verified) {
    throw new Error("Google did not return a verified email.");
  }

  const existingUser = await prisma.user.findFirst({
    where: { email: profile.email }
  });

  if (existingUser) {
    return existingUser;
  }

  return prisma.user.create({
    data: {
      userId: `u_${crypto.randomBytes(4).toString("hex")}`,
      name: profile.name ?? profile.email,
      email: profile.email,
      phone: "",
      passwordHash: crypto.randomBytes(32).toString("hex"),
      role: "customer"
    }
  });
}

export function createOAuthState(): string {
  return crypto.randomBytes(24).toString("hex");
}

export function googleRedirectUri(): string {
  if (process.env.GOOGLE_REDIRECT_URI) {
    return process.env.GOOGLE_REDIRECT_URI;
  }

  const baseUrl = process.env.PUBLIC_BASE_URL ?? "http://localhost:3000";
  return `${baseUrl}/auth/google/callback`;
}

function assertGoogleConfig(): void {
  if (!process.env.GOOGLE_CLIENT_ID || !process.env.GOOGLE_CLIENT_SECRET) {
    throw new Error("Google OAuth credentials are not configured.");
  }
}
