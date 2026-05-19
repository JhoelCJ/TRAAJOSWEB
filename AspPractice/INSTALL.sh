#!/bin/bash

# Script para crear estructura del proyecto ASP.NET MVC

BASE_DIR="$HOME/proyecto"
mkdir -p "$BASE_DIR"
cd "$BASE_DIR"

# Crear directorios
mkdir -p Models
mkdir -p Controllers
mkdir -p Services
mkdir -p Views/Employee
mkdir -p Views/Home
mkdir -p Views/Shared
mkdir -p wwwroot/css
mkdir -p wwwroot/js

echo "✅ Estructura del proyecto creada"
echo ""
echo "📁 Archivos a crear manualmente:"
echo "  - Models/Employee.cs"
echo "  - Models/ErrorViewModel.cs"
echo "  - Controllers/EmployeeController.cs"
echo "  - Controllers/HomeController.cs"
echo "  - Services/SupabaseService.cs"
echo "  - Views/Employee/Create.cshtml"
echo "  - Views/Employee/Index.cshtml"
echo "  - Views/Home/Index.cshtml"
echo "  - Views/Home/Privacy.cshtml"
echo "  - Views/Shared/_Layout.cshtml"
echo "  - Views/Shared/_ValidationScriptsPartial.cshtml"
echo "  - Views/Shared/Error.cshtml"
echo "  - wwwroot/css/site.css"
echo "  - wwwroot/js/employee-app.js"
