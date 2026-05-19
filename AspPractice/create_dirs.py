import os
import json

base_path = r'd:\UNIVERSIDAD\8vo Semestre (Quinto)\WebAv\U2\AspPractice'

# Directorios a crear
dirs = [
    'Models',
    'Controllers',
    'Services',
    'Views/Employee',
    'Views/Home',
    'Views/Shared',
    'wwwroot/css',
    'wwwroot/js'
]

for dir_path in dirs:
    full_path = os.path.join(base_path, dir_path)
    os.makedirs(full_path, exist_ok=True)
    print(f"✓ Created: {full_path}")

print("\n✅ All directories created successfully!")
