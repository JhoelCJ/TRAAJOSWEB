# Biconoir Gourmet - Aplicación Migrada a TypeScript + Vue 3 + Prisma

## 🚀 Descripción

Esta es la versión migrada de la aplicación Biconoir Gourmet, transformada de PHP puro a una arquitectura moderna con:

- **Frontend**: Vue 3 + TypeScript + Vite
- **Backend**: Express.js + TypeScript
- **ORM**: Prisma + PostgreSQL
- **Estado**: Pinia
- **Routing**: Vue Router

## 📋 Requisitos

- Node.js 16+ (recomendado 18+)
- npm o yarn
- PostgreSQL 12+
- Git

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone <tu-repo>
cd BiconoirsGourmet
```

### 2. Instalar dependencias

```bash
npm install
```

### 3. Configurar variables de entorno

Copia el archivo `.env.example` a `.env` y actualiza con tus credenciales:

```bash
cp .env.example .env
```

Actualiza los valores en `.env`:
```
DATABASE_URL="postgresql://usuario:contraseña@localhost:5432/biconoir_gourmet"
JWT_SECRET="tu-secreto-super-seguro-aqui"
NODE_ENV="development"
API_PORT=3000
CLIENT_PORT=5173
```

### 4. Configurar la base de datos

```bash
# Generar cliente Prisma
npm run prisma:generate

# Ejecutar migraciones
npm run prisma:migrate

# (Opcional) Cargar datos de prueba
npm run prisma:seed
```

## 🏃 Ejecución

### Modo desarrollo (ejecuta API + Frontend)

```bash
npm run dev
```

Esto inicia:
- **API**: http://localhost:3000
- **Frontend**: http://localhost:5173

### Modo producción

```bash
# Compilar backend
npm run server:build

# Compilar frontend
npm run client:build

# Ejecutar en producción
NODE_ENV=production node dist/server.js
```

## 📁 Estructura del Proyecto

```
BiconoirsGourmet/
├── src/
│   ├── api/
│   │   ├── controllers/       # Lógica de negocio
│   │   ├── middleware/        # Autenticación, CORS
│   │   └── routes/            # Rutas API
│   ├── components/            # Componentes Vue
│   │   ├── admin/            # Componentes administrativos
│   │   └── layouts/          # Layouts
│   ├── composables/          # Composables Vue
│   ├── config/               # Configuración
│   ├── database/
│   │   └── prisma/          # Cliente Prisma
│   ├── models/              # Modelos de datos
│   ├── router/              # Vue Router
│   ├── stores/              # Stores Pinia
│   ├── styles/              # Estilos globales
│   ├── types/               # Tipos TypeScript
│   ├── utils/               # Utilidades
│   ├── App.vue
│   ├── main.ts
│   └── server.ts
├── prisma/
│   └── schema.prisma        # Definición del esquema
├── index.html
├── package.json
├── tsconfig.json
├── vite.config.ts
└── .env.example
```

## 🔑 Características Principales

### Usuario/Autenticación
- ✅ Registro de usuarios
- ✅ Login con JWT
- ✅ Gestión de sesión

### Menú
- ✅ Visualizar platos disponibles
- ✅ Filtrar por categoría
- ✅ Agregar al carrito

### Carrito
- ✅ Gestión de carrito (Pinia Store)
- ✅ Checkout seguro
- ✅ Crear órdenes

### Órdenes
- ✅ Historial de órdenes
- ✅ Ver detalles de órdenes
- ✅ Cancelar órdenes
- ✅ Seguimiento de estado

### Reservas
- ✅ Crear reservas
- ✅ Ver mis reservas
- ✅ Cancelar reservas
- ✅ Máximo 2 reservas activas por usuario

### Admin
- ✅ Dashboard con estadísticas
- ✅ Gestión de órdenes
- ✅ Gestión de reservas
- ✅ Ver encuestas
- ✅ Gestión de inventario

### Encuestas
- ✅ Formulario de satisfacción
- ✅ Calificación y comentarios

## 🛠️ Scripts Disponibles

```bash
# Desarrollo
npm run dev              # Iniciar servidor dev + frontend
npm run server:dev      # Solo servidor Express
npm run client:dev      # Solo Vite dev server

# Producción
npm run server:build    # Compilar TypeScript
npm run client:build    # Compilar con Vite
npm run preview         # Preview de build

# Prisma
npm run prisma:generate # Generar cliente Prisma
npm run prisma:migrate  # Ejecutar migraciones
npm run prisma:studio   # Abrir Prisma Studio (GUI)
npm run prisma:seed     # Cargar datos de prueba

# Utilitarios
npm run type-check      # Verificar tipos TypeScript
npm run lint            # Ejecutar linter
```

## 🔐 Seguridad

- JWT para autenticación
- Contraseñas hasheadas con bcryptjs
- CORS configurado
- Validación de entrada en backend
- Guards de ruta en frontend

## 📊 Flujo de Autenticación

1. Usuario se registra → contraseña hasheada y guardada
2. Usuario inicia sesión → validación y JWT generado
3. Token guardado en localStorage
4. Headers de API incluyen `Authorization: Bearer <token>`
5. Backend valida token en cada request

## 🗄️ Base de Datos

### Tablas Principales
- `users` - Usuarios del sistema
- `dishes` - Platos del menú
- `ingredients` - Ingredientes
- `inventory_batches` - Lotes de inventario
- `orders` - Órdenes
- `order_details` - Detalles de órdenes
- `reservations` - Reservas
- `surveys` - Encuestas
- `financial_reports` - Reportes financieros
- `audit_logs` - Bitácora de cambios

## 🐛 Troubleshooting

### Problema: "DATABASE_URL is not defined"
**Solución**: Asegúrate de que `.env` existe y tiene la variable `DATABASE_URL`

### Problema: Error de conexión a PostgreSQL
**Solución**: Verifica que:
1. PostgreSQL está corriendo
2. Las credenciales en `.env` son correctas
3. La base de datos existe

### Problema: "Cannot find module '@components/...'"
**Solución**: Verifica que los alias en `vite.config.ts` y `tsconfig.json` son correctos

## 📝 Cambios Principales vs. Versión PHP

| Feature | PHP | TypeScript |
|---------|-----|-----------|
| Framework | Vanilla PHP | Express.js |
| Frontend | PHP templates + vanilla JS | Vue 3 + TypeScript |
| ORM | Illuminate/Database | Prisma |
| Enrutamiento | Query params (?action=) | Vue Router |
| Estado | $_SESSION | Pinia stores |
| Bundler | Ninguno | Vite |
| Tipos | Ninguno | TypeScript |
| Validación | Manual | TypeScript + runtime |

## 🚀 Deploy

### Heroku

```bash
# Agregar buildpack de Node.js
heroku buildpacks:add heroku/nodejs

# Configurar variables de entorno
heroku config:set DATABASE_URL=postgresql://...
heroku config:set JWT_SECRET=tu-secreto

# Deploy
git push heroku main
```

### Vercel (Frontend solamente)

```bash
npm run client:build
# Luego deploy la carpeta dist a Vercel
```

## 📞 Soporte

Para reportar bugs o sugerencias, por favor abre un issue en el repositorio.

## 📄 Licencia

MIT License - Biconoir Gourmet 2024

---

**Versión**: 2.0.0 (TypeScript + Vue 3)  
**Última actualización**: 2024
