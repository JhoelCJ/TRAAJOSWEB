import os
import sys

# Define base path
base_path = r'd:\UNIVERSIDAD\8vo Semestre (Quinto)\WebAv\U2\AspPractice'

# Files to create with their content
files = {
    'Models\\Employee.cs': '''namespace EmployeeManagement.Models
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
''',
    
    'Models\\ErrorViewModel.cs': '''namespace EmployeeManagement.Models;

public class ErrorViewModel
{
    public string? RequestId { get; set; }

    public bool ShowRequestId => !string.IsNullOrEmpty(RequestId);
}
''',

    'Controllers\\EmployeeController.cs': '''using EmployeeManagement.Models;
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
''',

    'Controllers\\HomeController.cs': '''using System.Diagnostics;
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
'''
}

# Create directories
dirs = ['Models', 'Controllers', 'Services', 'Views\\Employee', 'Views\\Home', 'Views\\Shared', 'wwwroot\\css', 'wwwroot\\js']
for d in dirs:
    full_path = os.path.join(base_path, d)
    os.makedirs(full_path, exist_ok=True)
    print(f"✓ Created: {full_path}")

# Create files
for filepath, content in files.items():
    full_path = os.path.join(base_path, filepath)
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"✓ File: {filepath}")

print("\n✅ All files created successfully!")
