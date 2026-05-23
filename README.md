# Overskull Back - Guia de arranque

Backend Laravel con Docker, Nginx, PostgreSQL y Supervisor.

## Requisitos

- Docker
- Docker Compose (plugin `docker compose`)

## Servicios del stack

- `nginx`: expone la app en `http://localhost:8080`
- `app`: PHP-FPM (Laravel)
- `db`: PostgreSQL 16
- `supervisor`: workers de cola (`queue:work`)

## Levantar el proyecto (primera vez)

1. Crear `.env` (si no existe):

```bash
cp .env.example .env
```

2. Levantar contenedores:

```bash
docker compose up --build -d
```

3. Instalar dependencias PHP:

```bash
docker compose exec app composer install
```

4. Generar clave de Laravel:

```bash
docker compose exec app php artisan key:generate
```

5. Ejecutar migraciones (y seed opcional):

```bash
docker compose exec app php artisan migrate
# opcional
docker compose exec app php artisan db:seed
```

6. Limpiar cache de configuracion:

```bash
docker compose exec app php artisan config:clear
```

La aplicacion quedara disponible en:

- `http://localhost:8080`

## Variables importantes de base de datos (Docker)

Dentro de contenedores, el backend usa PostgreSQL con:

- `DB_CONNECTION=pgsql`
- `DB_HOST=db`
- `DB_PORT=5432`
- `DB_DATABASE=overskull`
- `DB_USERNAME=root`
- `DB_PASSWORD=secret`

## CORS

La configuracion esta en `config/cors.php` y se controla desde `.env`:

- `CORS_ALLOWED_ORIGINS` (lista separada por comas o `*`)
- `CORS_SUPPORTS_CREDENTIALS` (`true`/`false`)

Ejemplo local:

```env
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000,http://localhost:8080
CORS_SUPPORTS_CREDENTIALS=false
```

## Comandos utiles

Ver logs:

```bash
docker compose logs -f
```

Entrar al contenedor PHP:

```bash
docker compose exec app bash
```

Parar contenedores:

```bash
docker compose down
```

Parar y borrar volumen de BD (reinicio total de datos):

```bash
docker compose down -v
```

## Troubleshooting rapido

- Si hay errores de conexion a BD al iniciar: espera a que `db` este `healthy` y revisa logs con `docker compose logs -f db app`.
- Si hay errores de permisos en Laravel:

```bash
docker compose exec app bash -lc "chmod -R ug+rw storage bootstrap/cache"
```
