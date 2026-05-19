# ARCHIVOS NECESARIOS PARA EL PROYECTO ASP.NET MVC + Vue + Supabase

Copiar cada contenido a su respectivo archivo en el proyecto.

## FILE: Models/Employee.cs
```csharp
namespace EmployeeManagement.Models
{
    public class Employee
    {
        public string? Id { get; set; }
        public string? Name { get; set; }
        public string? Address { get; set; }
        public string? Cellphone { get; set; }
        public string? Email { get; set; }
        public decimal Salary { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
```

## FILE: Models/ErrorViewModel.cs
```csharp
namespace EmployeeManagement.Models;

public class ErrorViewModel
{
    public string? RequestId { get; set; }

    public bool ShowRequestId => !string.IsNullOrEmpty(RequestId);
}
```

## FILE: Services/SupabaseService.cs
```csharp
using EmployeeManagement.Models;
using Supabase;
using Supabase.Gotrue;

namespace EmployeeManagement.Services
{
    public class SupabaseService
    {
        private readonly Client _client;

        public SupabaseService(IConfiguration configuration)
        {
            var url = configuration["Supabase:Url"];
            var key = configuration["Supabase:Key"];
            
            _client = new Client(url, key);
        }

        public async Task<List<Employee>> GetAllEmployeesAsync()
        {
            try
            {
                var response = await _client
                    .From<Employee>("employees")
                    .Select("*")
                    .Get();

                return response.Models ?? new List<Employee>();
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error fetching employees: {ex.Message}");
                return new List<Employee>();
            }
        }

        public async Task<Employee?> CreateEmployeeAsync(Employee employee)
        {
            try
            {
                employee.CreatedAt = DateTime.UtcNow;
                employee.UpdatedAt = DateTime.UtcNow;

                var response = await _client
                    .From<Employee>("employees")
                    .Insert(employee);

                return response.Models?.FirstOrDefault();
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error creating employee: {ex.Message}");
                return null;
            }
        }

        public async Task<bool> UpdateEmployeeAsync(Employee employee)
        {
            try
            {
                employee.UpdatedAt = DateTime.UtcNow;

                await _client
                    .From<Employee>("employees")
                    .Where(x => x.Id == employee.Id)
                    .Update(employee);

                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error updating employee: {ex.Message}");
                return false;
            }
        }

        public async Task<bool> DeleteEmployeeAsync(string id)
        {
            try
            {
                await _client
                    .From<Employee>("employees")
                    .Where(x => x.Id == id)
                    .Delete();

                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error deleting employee: {ex.Message}");
                return false;
            }
        }
    }
}
```

## FILE: Controllers/EmployeeController.cs
```csharp
using EmployeeManagement.Models;
using EmployeeManagement.Services;
using Microsoft.AspNetCore.Mvc;

namespace EmployeeManagement.Controllers
{
    public class EmployeeController : Controller
    {
        private readonly SupabaseService _supabaseService;

        public EmployeeController(SupabaseService supabaseService)
        {
            _supabaseService = supabaseService;
        }

        public async Task<IActionResult> Index()
        {
            var employees = await _supabaseService.GetAllEmployeesAsync();
            return View(employees);
        }

        public IActionResult Create()
        {
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> Create(Employee employee)
        {
            if (!ModelState.IsValid)
            {
                return View(employee);
            }

            var result = await _supabaseService.CreateEmployeeAsync(employee);
            
            if (result != null)
            {
                TempData["Success"] = "Empleado creado exitosamente";
                return RedirectToAction("Index");
            }
            
            ModelState.AddModelError("", "Error al crear el empleado");
            return View(employee);
        }
    }
}
```

## FILE: Controllers/HomeController.cs
```csharp
using System.Diagnostics;
using EmployeeManagement.Models;
using Microsoft.AspNetCore.Mvc;

namespace EmployeeManagement.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;

    public HomeController(ILogger<HomeController> logger)
    {
        _logger = logger;
    }

    public IActionResult Index()
    {
        return View();
    }

    public IActionResult Privacy()
    {
        return View();
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
```

## FILE: Views/_ViewStart.cshtml
```html
@{
    Layout = "_Layout";
}
```

## FILE: Views/Shared/_Layout.cshtml
```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@ViewData["Title"] - Gestión de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="~/css/site.css" asp-append-version="true" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Gestión de Empleados</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" asp-action="Index" asp-controller="Employee">Empleados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" asp-action="Create" asp-controller="Employee">Crear Empleado</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main role="main">
        @RenderBody()
    </main>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="text-muted">&copy; 2026 Gestión de Empleados - ASP.NET MVC + Vue + Supabase</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @await RenderSectionAsync("Scripts", required: false)
</body>
</html>
```

## FILE: Views/Shared/_ValidationScriptsPartial.cshtml
```html
<script src="https://cdn.jsdelivr.net/npm/jquery@4.1.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation-unobtrusive@4.0.0/jquery.validate.unobtrusive.min.js"></script>
```

## FILE: Views/Shared/Error.cshtml
```html
@model ErrorViewModel
@{
    ViewData["Title"] = "Error";
}

<h1 class="text-danger">Error.</h1>
<h2 class="text-danger">An error occurred while processing your request.</h2>

@if (Model?.ShowRequestId)
{
    <p>
        <strong>Request ID:</strong> <code>@Model?.RequestId</code>
    </p>
}

<h3>Development Mode</h3>
<p>
    Swapping to <strong>Development</strong> environment will display more detailed information about the error that occurred.
</p>
```

## FILE: Views/Home/Index.cshtml
```html
@{
    ViewData["Title"] = "Inicio";
}

<div class="container mt-5 text-center">
    <h1>Bienvenido a Gestión de Empleados</h1>
    <p class="lead mt-3">Aplicación ASP.NET MVC + Vue + Supabase</p>
    
    <div class="mt-5">
        <a asp-action="Index" asp-controller="Employee" class="btn btn-primary btn-lg">
            Ver Empleados
        </a>
        <a asp-action="Create" asp-controller="Employee" class="btn btn-success btn-lg ms-2">
            Crear Empleado
        </a>
    </div>
</div>
```

## FILE: Views/Home/Privacy.cshtml
```html
@{
    ViewData["Title"] = "Privacy Policy";
}
<h1>@ViewData["Title"]</h1>

<p>Use this page to detail your site's privacy policy.</p>
```

## FILE: Views/Employee/Create.cshtml
```html
@model EmployeeManagement.Models.Employee

@{
    ViewData["Title"] = "Crear Empleado";
}

<div class="container mt-5">
    <h1>Crear Nuevo Empleado</h1>

    <form asp-action="Create" asp-controller="Employee" method="post" class="mt-4">
        <div class="form-group mb-3">
            <label asp-for="Name" class="form-label">Nombre</label>
            <input asp-for="Name" class="form-control" placeholder="Nombre completo" required />
            <span asp-validation-for="Name" class="text-danger"></span>
        </div>

        <div class="form-group mb-3">
            <label asp-for="Email" class="form-label">Email</label>
            <input asp-for="Email" type="email" class="form-control" placeholder="correo@ejemplo.com" required />
            <span asp-validation-for="Email" class="text-danger"></span>
        </div>

        <div class="form-group mb-3">
            <label asp-for="Address" class="form-label">Dirección</label>
            <input asp-for="Address" class="form-control" placeholder="Dirección completa" required />
            <span asp-validation-for="Address" class="text-danger"></span>
        </div>

        <div class="form-group mb-3">
            <label asp-for="Cellphone" class="form-label">Teléfono</label>
            <input asp-for="Cellphone" class="form-control" placeholder="Número de teléfono" required />
            <span asp-validation-for="Cellphone" class="text-danger"></span>
        </div>

        <div class="form-group mb-3">
            <label asp-for="Salary" class="form-label">Salario</label>
            <input asp-for="Salary" type="number" step="0.01" class="form-control" placeholder="0.00" required />
            <span asp-validation-for="Salary" class="text-danger"></span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Guardar Empleado</button>
            <a asp-action="Index" asp-controller="Employee" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@section Scripts {
    @{
        await Html.RenderPartialAsync("_ValidationScriptsPartial");
    }
}
```

## FILE: Views/Employee/Index.cshtml
```html
@model List<EmployeeManagement.Models.Employee>

@{
    ViewData["Title"] = "Lista de Empleados";
}

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Empleados</h1>
        <a asp-action="Create" asp-controller="Employee" class="btn btn-primary">+ Crear Empleado</a>
    </div>

    @if (TempData["Success"] != null)
    {
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            @TempData["Success"]
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    }

    <div id="app">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th class="text-end">Salario</th>
                    </tr>
                </thead>
                <tbody>
                    @if (Model != null && Model.Count > 0)
                    {
                        @foreach (var employee in Model)
                        {
                            <tr>
                                <td>@employee.Name</td>
                                <td>@employee.Email</td>
                                <td>@employee.Address</td>
                                <td>@employee.Cellphone</td>
                                <td class="text-end">
                                    $@employee.Salary.ToString("N2")
                                </td>
                            </tr>
                        }
                    }
                    else
                    {
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay empleados registrados. <a asp-action="Create">Crear uno ahora</a>
                            </td>
                        </tr>
                    }
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td colspan="4" class="text-end">Total de Salarios:</td>
                        <td class="text-end">
                            $<span id="totalSalary">
                                @if (Model != null && Model.Count > 0)
                                {
                                    @Model.Sum(e => e.Salary).ToString("N2")
                                }
                                else
                                {
                                    @"0.00"
                                }
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section Scripts {
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="~/js/employee-app.js"></script>
}
```

## FILE: wwwroot/css/site.css
```css
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
}

main {
    min-height: calc(100vh - 120px);
}

.table {
    background-color: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background-color: #0d6efd;
}

.btn-primary:hover {
    background-color: #0b5ed7;
}

footer {
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
}

.table-responsive {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.alert {
    border-radius: 5px;
}
```

## FILE: wwwroot/js/employee-app.js
```javascript
const { createApp } = Vue;

createApp({
    data() {
        return {
            employees: [],
            totalSalary: 0
        }
    },
    mounted() {
        this.loadEmployees();
    },
    methods: {
        loadEmployees() {
            this.calculateTotalSalary();
        },
        calculateTotalSalary() {
            const salaryElements = document.querySelectorAll('tbody tr td:nth-child(5)');
            let total = 0;

            salaryElements.forEach(element => {
                const text = element.textContent.replace('$', '').trim();
                const salary = parseFloat(text.replace(/,/g, ''));
                if (!isNaN(salary)) {
                    total += salary;
                }
            });

            const totalElement = document.getElementById('totalSalary');
            if (totalElement) {
                totalElement.textContent = total.toFixed(2);
            }

            this.totalSalary = total;
        }
    }
}).mount('#app');
```

## SQL para Supabase (Crear tabla employees)
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
