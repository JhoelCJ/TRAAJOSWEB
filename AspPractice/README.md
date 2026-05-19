# ASP.NET MVC + Vue + Supabase Project

This project was created successfully. Follow these steps to finish the setup:

## 1. Install Dependencies

Open a terminal in the project root and run:

```bash
dotnet restore
```

## 2. Configure Supabase

Edit `appsettings.json` with your Supabase credentials:

```json
{
  "Supabase": {
    "Url": "https://YOUR_PROJECT.supabase.co",
    "Key": "YOUR_ANON_KEY",
    "ServiceRoleKey": "YOUR_SERVICE_ROLE_KEY"
  }
}
```

Get these values from your Supabase dashboard.

## 3. Create Table in Supabase

Run this SQL in your Supabase database:

```sql
CREATE TABLE employees (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  address TEXT,
  cellphone TEXT,
  salary DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);
```

## 4. Run the Application

```bash
dotnet run
```

The app will be available at `https://localhost:7000`

## Project Structure

- **Controllers/**: Application logic
- **Models/**: Data models
- **Services/**: Supabase connection service
- **Views/**: Razor views
- **wwwroot/**: Static files (CSS, JS)

## Features

✅ Create employees
✅ View employee list in a table
✅ Automatically calculate total salaries
✅ Vue 3 integration
✅ Direct Supabase connection (no intermediate API)
✅ Responsive design with Bootstrap 5
