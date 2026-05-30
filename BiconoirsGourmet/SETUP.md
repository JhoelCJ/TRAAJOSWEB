# 🚀 Guía de Configuración e Instalación

## Pasos para ejecutar Biconoir Gourmet (TypeScript + Vue 3 + Prisma)

### Paso 1: Preparar el Entorno

```bash
# 1.1 Asegúrate de tener Node.js 18+ instalado
node --version

# 1.2 Asegúrate de tener npm actualizado
npm --version
npm install -g npm@latest

# 1.3 Asegúrate de que PostgreSQL está corriendo
# En Windows: Services > PostgreSQL
# En Linux: sudo service postgresql start
# En Mac: brew services start postgresql
```

### Paso 2: Instalar Dependencias

```bash
# 2.1 Navega a la carpeta del proyecto
cd BiconoirsGourmet

# 2.2 Instala todas las dependencias
npm install

# Este comando instalará:
# - Express, Prisma, JWT para el backend
# - Vue 3, Pinia, Vue Router para el frontend
# - Vite como bundler
# - TypeScript y todas las herramientas necesarias
```

### Paso 3: Configurar Variables de Entorno

```bash
# 3.1 Crea el archivo .env
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env

# 3.2 Edita .env y actualiza estos valores:
# DATABASE_URL=postgresql://usuario:password@localhost:5432/biconoir_gourmet
# JWT_SECRET=tu-secreto-super-seguro-cambia-esto-en-produccion
# NODE_ENV=development
```

**Nota**: Si usas Supabase (PostgreSQL en la nube):
```
DATABASE_URL="postgresql://postgres.xxx:password@aws-1-us-east-1.pooler.supabase.com:5432/postgres"
```

### Paso 4: Configurar la Base de Datos

```bash
# 4.1 Generar el cliente Prisma
npm run prisma:generate

# 4.2 Ejecutar migraciones (crea las tablas)
npm run prisma:migrate

# Cuando te pregunte el nombre: "initial"

# 4.3 (Opcional) Cargar datos de prueba
npm run prisma:seed

# Este script crea:
# - 1 usuario admin (admin@biconoir.com / admin123)
# - 1 usuario cliente (customer@example.com / customer123)
# - 3 platos de prueba
# - 3 ingredientes de prueba
```

### Paso 5: Ejecutar el Proyecto

**Opción A: Desarrollo (Recomendado)**

```bash
# Inicia automáticamente:
# - Backend API en http://localhost:3000
# - Frontend en http://localhost:5173
npm run dev

# Espera a ver ambos mensajes:
# "Biconoir Gourmet API Server running on port 3000"
# "VITE v5.0.0 ready in XXXms"
```

**Opción B: Solo Backend**

```bash
npm run server:dev
# API disponible en http://localhost:3000/api/health
```

**Opción C: Solo Frontend**

```bash
npm run client:dev
# Frontend en http://localhost:5173
# (Necesita que el backend esté corriendo en otra terminal)
```

### Paso 6: Acceder a la Aplicación

Abre el navegador en:
```
http://localhost:5173
```

**Credenciales de Prueba:**

Admin:
```
Email: admin@biconoir.com
Password: admin123
```

Cliente:
```
Email: customer@example.com
Password: customer123
```

### Paso 7: Explorar las Características

1. **Como Cliente:**
   - Ir a /menu → Ver platos y agregarlos al carrito
   - Ir a /cart → Hacer checkout
   - Ir a /reservations → Hacer una reserva
   - Ir a /orders → Ver mis órdenes

2. **Como Admin:**
   - Ir a /admin/dashboard → Ver estadísticas
   - Admin Dashboard → Órdenes: cambiar estado de órdenes
   - Admin Dashboard → Reservas: ver todas las reservas
   - Admin Dashboard → Encuestas: ver calificaciones

## 🛠️ Comandos Útiles

```bash
# Prisma Studio (GUI para base de datos)
npm run prisma:studio
# Abre en http://localhost:5555

# Verificar tipos TypeScript
npm run type-check

# Build para producción
npm run client:build
npm run server:build

# Reset completo de base de datos
npm run prisma:migrate reset
# Esto elimina todos los datos y re-crea las tablas
```

## 🐛 Solución de Problemas

### Error: "DATABASE_URL is not defined"
```bash
# Solución:
# 1. Verifica que existe el archivo .env
# 2. Verifica que DATABASE_URL está en .env
# 3. Reinicia el servidor
```

### Error: "Cannot connect to PostgreSQL"
```bash
# Solución:
# 1. Verifica que PostgreSQL está corriendo
# 2. Verifica las credenciales en DATABASE_URL
# 3. Verifica que la base de datos existe:
psql -U postgres -h localhost -d biconoir_gourmet
```

### Error: "Port 3000/5173 already in use"
```bash
# Solución: Usa puertos diferentes en .env
API_PORT=3001
CLIENT_PORT=5174

# O mata el proceso:
# Windows: netstat -ano | findstr :3000
# Linux/Mac: lsof -i :3000
```

### El carrito no persiste después de recargar
```bash
# Esto es normal en desarrollo
# En producción, se puede:
# 1. Guardar en localStorage (ya implementado)
# 2. Guardar en base de datos
# 3. Sincronizar con servidor
```

## 📊 Estructura de Carpetas Importantes

```
src/
├── api/              ← Backend Express
├── components/       ← Componentes Vue (Frontend)
├── stores/           ← Pinia stores (estado global)
├── composables/      ← Lógica reutilizable
├── utils/            ← Funciones auxiliares
├── main.ts          ← Punto de entrada Vue
└── server.ts        ← Punto de entrada Express

prisma/
└── schema.prisma    ← Definición de datos
```

## 🔐 Credenciales Predeterminadas

Después de ejecutar `npm run prisma:seed`:

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@biconoir.com | admin123 |
| Cliente | customer@example.com | customer123 |

## 📝 Variables de Entorno Disponibles

```bash
# Base de datos
DATABASE_URL=postgresql://...

# Seguridad
JWT_SECRET=tu-secreto-aqui

# Entorno
NODE_ENV=development|production

# Puertos
API_PORT=3000
CLIENT_PORT=5173
```

## ✅ Checklist de Instalación

- [ ] Node.js 18+ instalado
- [ ] PostgreSQL corriendo
- [ ] npm install ejecutado
- [ ] .env configurado
- [ ] npm run prisma:generate ejecutado
- [ ] npm run prisma:migrate ejecutado
- [ ] (Opcional) npm run prisma:seed ejecutado
- [ ] npm run dev sin errores
- [ ] Puedo acceder a http://localhost:5173
- [ ] Puedo iniciar sesión con admin@biconoir.com

## 🚀 Próximos Pasos

Después de configurar todo:

1. Familiarizarte con la interfaz
2. Crear un usuario de prueba
3. Hacer un pedido de prueba
4. Explorar el panel de admin
5. Revisar el código para entender la arquitectura

¡Disfruta explorando Biconoir Gourmet! 🍽️
