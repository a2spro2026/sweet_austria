Set-Location $PSScriptRoot
Write-Host "nador (Sweet Austria) -> http://127.0.0.1:8000" -ForegroundColor Green
php artisan serve --host=127.0.0.1 --port=8000
