# PHP + Vue + Supabase + RedBean MVC

Ejemplo de aplicacion web sin API REST: PHP sirve las paginas MVC, los formularios envian `POST` normal al controlador, RedBean guarda/lee datos desde Supabase Postgres y Vue se usa en las vistas para la experiencia de usuario.

## 1. Crear la tabla en Supabase

Abre Supabase SQL Editor y ejecuta:

```sql
create table if not exists employee (
  id bigserial primary key,
  employee_id varchar(80) not null unique,
  name varchar(160) not null,
  address varchar(220) not null,
  cellphone varchar(40) not null,
  email varchar(180) not null,
  salary numeric(12,2) not null default 0,
  created_at timestamptz not null default now()
);
```

## 2. Configurar credenciales

```powershell
Copy-Item .env.example .env
```

Luego edita `.env` con los datos de Supabase.

## 3. Agregar RedBean sin Composer

Copia tu archivo `rb.php` en una de estas rutas:

```text
vendor/rb.php
vendor/gabordemooij/redbean/rb.php
lib/rb.php
rb.php
```

La ruta recomendada para este proyecto es:

```text
lib/rb.php
```

Si quieres usar otra ubicacion, configura `REDBEAN_FILE` en `.env` con la ruta absoluta del archivo.

Si PHP no tiene PostgreSQL activo, habilita `pdo_pgsql` y `pgsql` en `php.ini`.

## 4. Ejecutar

```powershell
php -S localhost:8000 -t public
```

Abre:

- `http://localhost:8000/employees/create` para registrar empleados.
- `http://localhost:8000/employees` para ver la tabla y el total de salarios.

## Estructura MVC

- `public/index.php`: front controller y rutas.
- `app/Controllers/EmployeeController.php`: recibe peticiones y decide la respuesta.
- `app/Models/Employee.php`: acceso a datos usando RedBean.
- `app/Views`: HTML/PHP con Vue montado en cada pagina.
- `app/Core`: configuracion, base de datos y helpers de vista.

## Desplegar en Render con Docker

1. Copia `rb.php` en `lib/rb.php` y subelo al repositorio.
2. En Render crea un Web Service usando Docker.
3. Configura estas variables de entorno:

```text
APP_ENV=production
APP_DEBUG=false
SUPABASE_DATABASE_URL=postgresql://postgres.TU_PROJECT_REF:tu_password_de_base_de_datos@aws-0-TU_REGION.pooler.supabase.com:5432/postgres?sslmode=require
```

Tambien puedes usar variables separadas:

```text
APP_ENV=production
APP_DEBUG=false
SUPABASE_DB_HOST=aws-0-TU_REGION.pooler.supabase.com
SUPABASE_DB_PORT=5432
SUPABASE_DB_NAME=postgres
SUPABASE_DB_USER=postgres.TU_PROJECT_REF
SUPABASE_DB_PASSWORD=tu_password_de_base_de_datos
SUPABASE_DB_SSLMODE=require
```

Usa el connection string de Supabase en modo Session Pooler si Render no puede conectar por IPv6 con la conexion directa.
