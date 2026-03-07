# Camping Herradura MarySol

Guía rápida para instalar dependencias y levantar el proyecto localmente.

## 1) Requisitos

- macOS
- Homebrew instalado
- MySQL (u otro motor compatible con Laravel)

## 2) Instalar PHP

```bash
brew update
brew install php
php -v
```

> Si necesitas una versión específica (por ejemplo 8.3):

```bash
brew install php@8.3
brew link --overwrite --force php@8.3
php -v
```

## 3) Instalar Composer

```bash
brew install composer
composer --version
```

## 4) Instalar Laravel (global)

```bash
composer global require laravel/installer
```

Agrega Composer global al `PATH` (si no lo tienes):

```bash
echo 'export PATH="$HOME/.composer/vendor/bin:$HOME/.config/composer/vendor/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
laravel --version
```

## 5) Instalar dependencias del proyecto

Ubícate en la raíz del proyecto y ejecuta:

```bash
composer install
cp .env.example .env
php artisan key:generate
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

```bash
php artisan migrate:fresh --seed
```

### Opción normal (solo migrar + seed)

```bash
php artisan migrate
php artisan db:seed
```

## 8) Levantar el proyecto

```bash
php artisan serve
```

Aplicación disponible en:

- http://127.0.0.1:8000

## 9) Comandos útiles

Limpiar cachés:

```bash
php artisan optimize:clear
php artisan view:clear
php artisan permission:cache-reset
```

---

Si ocurre un error de permisos/roles después de sembrar, ejecuta nuevamente:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=PermissionsHospedajeProductoSeeder
php artisan permission:cache-reset
```
