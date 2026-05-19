using EmployeeManagement.Models;
using System.Net.Http;
using System.Net.Http.Json;
using System.Net.Http.Headers;
using System.Text.Json;
using Microsoft.Extensions.Configuration;


namespace EmployeeManagement.Services
{
    public class SupabaseService
    {
        private readonly HttpClient _httpClient;
        private readonly string _baseUrl;
        private readonly string _apiKey;
        private const string Table = "employees";

        public SupabaseService(IConfiguration configuration)
        {
            var url = configuration["https://teqgmzwpcwcelrpecdyq.supabase.co"];
            var key = configuration["eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InRlcWdtendwY3djZWxycGVjZHlxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Nzg0NTExOTQsImV4cCI6MjA5NDAyNzE5NH0.ROxh_zCVrtIF8U67hmT1DkgA4-4OWwpL2R0kQWIPIGw"];

            if (string.IsNullOrEmpty(url) || string.IsNullOrEmpty(key))
            {
                throw new ArgumentException("Supabase configuration missing. Please set 'Supabase:Url' and 'Supabase:Key' in appsettings.json or environment variables.");
            }

            _baseUrl = url.TrimEnd('/');
            _apiKey = key;

            _httpClient = new HttpClient
            {
                BaseAddress = new Uri(_baseUrl)
            };

            _httpClient.DefaultRequestHeaders.Add("apikey", _apiKey);
            _httpClient.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", _apiKey);
            _httpClient.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));
        }

        public async Task<List<Employee>> GetAllEmployeesAsync()
        {
            try
            {
                var resp = await _httpClient.GetAsync($"/rest/v1/{Table}?select=*");
                if (!resp.IsSuccessStatusCode) return new List<Employee>();

                var list = await resp.Content.ReadFromJsonAsync<List<Employee>>();
                return list ?? new List<Employee>();
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

                var request = new HttpRequestMessage(HttpMethod.Post, $"/rest/v1/{Table}")
                {
                    Content = JsonContent.Create(employee, options: new JsonSerializerOptions { PropertyNamingPolicy = JsonNamingPolicy.CamelCase })
                };
                request.Headers.Add("Prefer", "return=representation");

                var resp = await _httpClient.SendAsync(request);
                if (!resp.IsSuccessStatusCode) return null;

                var created = await resp.Content.ReadFromJsonAsync<List<Employee>>();
                return created?.FirstOrDefault();
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

                var request = new HttpRequestMessage(new HttpMethod("PATCH"), $"/rest/v1/{Table}?id=eq.{employee.Id}")
                {
                    Content = JsonContent.Create(employee, options: new JsonSerializerOptions { PropertyNamingPolicy = JsonNamingPolicy.CamelCase })
                };
                request.Headers.Add("Prefer", "return=representation");

                var resp = await _httpClient.SendAsync(request);
                return resp.IsSuccessStatusCode;
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
                var resp = await _httpClient.DeleteAsync($"/rest/v1/{Table}?id=eq.{id}");
                return resp.IsSuccessStatusCode;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error deleting employee: {ex.Message}");
                return false;
            }
        }
    }
}
