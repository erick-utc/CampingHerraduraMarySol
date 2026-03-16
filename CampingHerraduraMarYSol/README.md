# Camping Herradura MarySol

Guía rápida para instalar dependencias y levantar el proyecto localmente.

## 1) Requisitos

- Windows 10/11
- PowerShell o Command Prompt
- MySQL (u otro motor compatible con Laravel)

## 2) Instalar PHP en Windows

### Opción recomendada: usar XAMPP (más simple)

1. Descarga e instala XAMPP desde https://www.apachefriends.org
2. Abre el panel de XAMPP y enciende al menos `Apache` y `MySQL`.
3. Verifica PHP en terminal:

```powershell
php -v
```

Si `php` no se reconoce, agrega al `PATH` la carpeta de PHP de XAMPP, normalmente:

`C:\xampp\php`

### Opción alternativa: PHP manual

1. Descarga PHP para Windows desde https://windows.php.net/download
2. Extrae en una carpeta, por ejemplo `C:\php`.
3. Agrega `C:\php` al `PATH` del sistema.
4. Cierra y abre de nuevo la terminal, luego valida:

```powershell
php -v
```

## 3) Instalar Composer en Windows

1. Descarga el instalador desde https://getcomposer.org/Composer-Setup.exe
2. Durante la instalación, selecciona la ruta de `php.exe` (XAMPP o manual).
3. Verifica instalación:

```powershell
composer --version
```

## 4) Instalar Laravel (global)

```powershell
composer global require laravel/installer
```

Agrega Composer global al `PATH` (si no lo detecta automáticamente):

- `C:\Users\TU_USUARIO\AppData\Roaming\Composer\vendor\bin`

Después, abre una nueva terminal y valida:

```powershell
laravel --version
```

## 5) Instalar dependencias del proyecto

Ubícate en la raíz del proyecto y ejecuta:

```powershell
composer install
copy .env.example .env
php artisan key:generate
```

> Si `copy` no funciona en PowerShell, usa:

```powershell
Copy-Item .env.example .env
```

## 6) Configurar base de datos

Edita el archivo `.env` con tus credenciales:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camping_herradura
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de datos en MySQL antes de migrar.

## 7) Migrar y poblar (seeders)

### Opción recomendada (limpia y recrea todo)

```powershell
php artisan migrate:fresh --seed
```

### Opción normal (solo migrar + seed)

```powershell
php artisan migrate
php artisan db:seed
```

## 8) Levantar el proyecto

```powershell
php artisan serve
```

Aplicación disponible en:

- http://127.0.0.1:8000

## 9) Comandos útiles

Limpiar cachés:

```powershell
php artisan optimize:clear
php artisan view:clear
php artisan permission:cache-reset
```

---

Si ocurre un error de permisos/roles después de sembrar, ejecuta nuevamente:

```powershell
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=PermissionsHospedajeProductoSeeder
php artisan permission:cache-reset
```
