import "dotenv/config";
import express from "express";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { buildGoogleAuthorizationUrl, createOAuthState, signInWithGoogle } from "./google-auth.js";
import { sessionMiddleware } from "./session.js";

const app = express();
const port = Number(process.env.PORT ?? "3000");
const __dirname = path.dirname(fileURLToPath(import.meta.url));
const publicPath = path.resolve(__dirname, "../public");

app.disable("x-powered-by");
app.set("trust proxy", 1);
app.use(express.urlencoded({ extended: true }));
app.use("/css", express.static(path.join(publicPath, "css")));
app.use("/img", express.static(path.join(publicPath, "img")));
app.use(sessionMiddleware);

app.get("/", (req, res) => {
  if (!req.session.user) {
    return res.redirect("/login");
  }

  res.send(pageShell(`
    <main class="page">
      <section class="hero">
        <img src="/img/logoRestaurantGreen.png" alt="Biconoirs Gourmet" class="logo" />
        <h1>Biconoir's Gourmet</h1>
        <p>Bienvenido, ${escapeHtml(req.session.user.name)}.</p>
        <form method="post" action="/logout">
          <button class="primary-button" type="submit">Cerrar sesion</button>
        </form>
      </section>
    </main>
  `));
});

app.get("/login", (req, res) => {
  const error = typeof req.query.error === "string" ? req.query.error : "";

  res.send(pageShell(`
    <main class="page">
      <section class="auth-card">
        <img src="/img/logoRestaurantGreen.png" alt="Biconoirs Gourmet" class="logo" />
        <h1>Iniciar sesion</h1>
        <p>Accede con Google para continuar.</p>
        ${error ? `<div class="error">${escapeHtml(error)}</div>` : ""}
        <a class="google-button" href="/auth/google">Continuar con Google</a>
      </section>
    </main>
  `));
});

app.get("/auth/google", (req, res) => {
  try {
    const state = createOAuthState();
    req.session.oauthState = state;
    res.redirect(buildGoogleAuthorizationUrl(state));
  } catch (error) {
    res.redirect(`/login?error=${encodeURIComponent(errorMessage(error))}`);
  }
});

app.get("/auth/google/callback", async (req, res) => {
  try {
    const state = String(req.query.state ?? "");
    const code = String(req.query.code ?? "");

    if (!state || state !== req.session.oauthState) {
      throw new Error("Invalid OAuth state.");
    }

    if (!code) {
      throw new Error("Google did not return an authorization code.");
    }

    const user = await signInWithGoogle(code);
    req.session.oauthState = undefined;
    req.session.user = {
      userId: user.userId,
      name: user.name,
      email: user.email,
      role: user.role
    };

    res.redirect("/");
  } catch (error) {
    res.redirect(`/login?error=${encodeURIComponent(errorMessage(error))}`);
  }
});

app.post("/logout", (req, res) => {
  req.session.destroy(() => {
    res.clearCookie("biconoir_session");
    res.redirect("/login");
  });
});

app.get("/health", (_req, res) => {
  res.status(200).send("ok");
});

app.listen(port, "0.0.0.0", () => {
  console.log(`Server running on port ${port}`);
});

function pageShell(content: string): string {
  return `<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Biconoir's Gourmet</title>
  <link rel="stylesheet" href="/css/auth.css" />
</head>
<body>
  ${content}
</body>
</html>`;
}

function escapeHtml(value: string): string {
  return value
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function errorMessage(error: unknown): string {
  return error instanceof Error ? error.message : "Unexpected authentication error.";
}
