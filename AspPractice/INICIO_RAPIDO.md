# 🚀 STEP-BY-STEP GUIDE - Final Setup

## ✅ Files Already Created

The following files already exist in your project:

```
✓ EmployeeManagement.csproj
✓ Program.cs (updated with SupabaseService)
✓ appsettings.json
✓ README.md
```

## 📋 STEP 1: Run Generation Script

1. Open **CMD** as administrator
2. Navigate to: `d:\UNIVERSIDAD\8vo Semestre (Quinto)\WebAv\U2\AspPractice`
3. Run: `SETUP_COMPLETE.bat`

This script will create:
- ✅ All folders
- ✅ Models (Employee.cs, ErrorViewModel.cs)
- ✅ Controllers (EmployeeController.cs, HomeController.cs)
- ✅ Views (all Razor views)
- ✅ wwwroot (CSS and JS)

## 📋 STEP 2: Create Supabase Service Manually

Copy the contents of **SupabaseService_CODE.txt** and create:

**File:** `Services\SupabaseService.cs`

(The script might not create files with complex code, so a text file is provided.)

## 📋 STEP 3: Configure Supabase

### 3.1 Get your Supabase credentials:

1. Go to https://supabase.com
2. Create a new project or open an existing one
3. Go to **Settings > API** and copy:
   - `Project URL` (paste into `Supabase:Url`)
   - `anon public` key (paste into `Supabase:Key`)

### 3.2 Edit `appsettings.json`:

```json
{
  "Logging": {
    "LogLevel": {
      "Default": "Information",
      "Microsoft.AspNetCore": "Warning"
    }
  },
  "AllowedHosts": "*",
  "Supabase": {
    "Url": "https://YOUR_PROJECT_ID.supabase.co",
    "Key": "YOUR_ANON_KEY",
    "ServiceRoleKey": "YOUR_SERVICE_ROLE_KEY"
  }
}
```

Replace:
- `YOUR_PROJECT_ID` - your Supabase project ID
- `YOUR_ANON_KEY` - the public anon key
- `YOUR_SERVICE_ROLE_KEY` - the service role key

### 3.3 Create the table in Supabase:

1. In Supabase, go to **SQL Editor**
2. Create a new query
3. Copy and run this SQL:

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

4. Enable RLS (Row Level Security) if needed:

```sql
ALTER TABLE employees ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Enable read access for all users" ON employees
  FOR SELECT USING (true);

CREATE POLICY "Enable insert for all users" ON employees
  FOR INSERT WITH CHECK (true);

CREATE POLICY "Enable update for all users" ON employees
  FOR UPDATE USING (true);

CREATE POLICY "Enable delete for all users" ON employees
  FOR DELETE USING (true);
```

## 📋 STEP 4: Install Dependencies

Open a terminal in the project folder:

```bash
dotnet restore
```

## 📋 STEP 5: Run the Application

```bash
dotnet run
```

Or in Visual Studio:
1. Open Visual Studio
2. File > Open Project/Solution
3. Select `EmployeeManagement.csproj`
4. Press F5 or Debug > Start Debugging

## 📋 STEP 6: Test the Application

1. Open `https://localhost:7000` or `https://localhost:7001`
2. Click "Create Employee"
3. Fill the form
4. Save
5. Go to "Employees" to see the table with total salaries calculated

## 🎯 Main URLs

- **Home:** https://localhost:7000/
- **Employee List:** https://localhost:7000/employee/index
- **Create Employee:** https://localhost:7000/employee/create

## 📁 Final Structure

```
EmployeeManagement/
├── Models/
│   ├── Employee.cs
│   └── ErrorViewModel.cs
├── Controllers/
│   ├── EmployeeController.cs
│   └── HomeController.cs
├── Services/
│   └── SupabaseService.cs
├── Views/
│   ├── _ViewStart.cshtml
│   ├── Employee/
│   │   ├── Create.cshtml
│   │   └── Index.cshtml
│   ├── Home/
│   │   ├── Index.cshtml
│   │   └── Privacy.cshtml
│   └── Shared/
│       ├── _Layout.cshtml
│       ├── _ValidationScriptsPartial.cshtml
│       └── Error.cshtml
├── wwwroot/
│   ├── css/
│   │   └── site.css
│   └── js/
│       └── employee-app.js
├── EmployeeManagement.csproj
├── Program.cs
├── appsettings.json
└── README.md
```

## ⚠️ Troubleshooting

### Error: "SupabaseService not found"
- Make sure the file `Services/SupabaseService.cs` exists
- Verify it uses the correct namespace

### Error: "Table employees does not exist"
- Run the creation SQL in Supabase
- Verify the table name matches exactly

### Error: "Invalid credentials"
- Verify URL and keys in `appsettings.json`
- Copy them from Supabase > Settings > API

### Problem: Table not updating
- Ensure RLS is enabled correctly
- Verify access policies

## ✨ Implemented Features

✅ Create employees with name, email, address, phone, salary
✅ List all employees in a table
✅ Automatically calculate total salaries
✅ Responsive design with Bootstrap 5
✅ Vue 3 integration
✅ Direct Supabase connection (no intermediate REST API)
✅ Pure ASP.NET MVC structure

## 📞 Next Steps (Optional)

- Add edit employee
- Add delete employee
- Add search/filters
- Add pagination
- Export data to Excel

---

Ready to go! 🎉
