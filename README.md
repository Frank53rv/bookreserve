## BookReserve

BookReserve es una aplicacion web construida con Laravel 12 y Vite que sirve como punto de partida para gestionar reservas de libros.

## Requisitos

- PHP 8.2 o superior con las extensiones `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`
- Composer 2.6 o superior
- Node.js 20.x y npm 10.x
- SQLite, MySQL o PostgreSQL para la base de datos

## Puesta en marcha

1. Instala dependencias de PHP: `composer install`
2. Crea un archivo de entorno: `cp .env.example .env`
3. Genera la clave de la aplicacion: `php artisan key:generate`
4. Configura la base de datos en `.env`
5. Ejecuta migraciones: `php artisan migrate`
6. Instala dependencias front-end: `npm install`
7. Lanza los servidores: `php artisan serve` y `npm run dev`

## Scripts disponibles

- `composer run dev` levanta servidor HTTP, escucha colas, registra logs con Pail y ejecuta Vite en modo desarrollo.
- `composer run setup` instala dependencias, genera la clave y construye los assets de front-end.
- `php artisan test` ejecuta la suite de pruebas.

## Estructura del proyecto

- `app/` contiene el codigo principal de la aplicacion.
- `database/` almacena migraciones, seeders y archivos SQLite.
- `resources/` incluye vistas Blade, componentes de JavaScript y estilos.
- `routes/` define las rutas HTTP y consola.


## API y controladores

- `CategoryController`, `BookController`, `ClientController`, `ReservationHeaderController`, `ReservationDetailController`, `ReturnHeaderController`, `ReturnDetailController`, `InventoryRecordController` y `MovementController` exponen operaciones CRUD. Cada accion responde con JSON para facilitar el consumo desde front-end o servicios externos.
- Las rutas API viven en `routes/api.php` y se registran via `Route::apiResources`, generando endpoints RESTful (`GET /api/categories`, `POST /api/books`, etc.).
- Todas las rutas usan el grupo de middleware `api` definido por Laravel: incluye `throttle:api`, control de estado sin sesiones y formato de respuesta en JSON.
- Para invocar los endpoints basta con hacer solicitudes HTTP con encabezado `Accept: application/json`; las respuestas incluiran datos serializados y codigos de estado apropiados (201 al crear, 204 al eliminar).
- Si no deseas trabajar con JSON o APIs, puedes crear controladores de tipo web con vistas Blade y registrarlos en `routes/web.php` usando el middleware `web`.

TO do 
Autenticación y Roles (fundamental para seguridad)
Sistema de Multas (genera ingresos adicionales)
Reportes Avanzados (ya tienes dashboard, complementaría bien) 