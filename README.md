# 🎬 Movies & Series Platform - Backend

API RESTful construida con Laravel 11 para gestionar películas, series y usuarios.

## Descripción

CineVerse API provee toda la lógica backend para una aplicación full-stack de seguimiento de películas y series. Se integra con la **TMDB API** para obtener datos en tiempo real, y gestiona autenticación de usuarios, favoritos, listas de seguimiento, reseñas e historial de búsqueda.

El frontend (React + Vite) está disponible en: [movies-series-frontend](https://github.com/yergiroska/movies-series-frontend).

## 🚀 Tecnologías

- Laravel 11.31.0
- PHP 8.2.12
- MySQL / SQLite
- Laravel Sanctum (Autenticación)
- TMDB API Integration

## ⚙️ Características

- ✅ Sistema de autenticación completo (registro, login, logout)
- ✅ Integración con TMDB API
- ✅ Gestión de favoritos
- ✅ Lista de seguimiento personalizada
- ✅ Búsqueda de películas y series
- ✅ Historial de búsquedas del usuario

## 📦 Instalación
```bash
# Clonar el repositorio
git clone https://github.com/TU-USUARIO/movies-series-backend.git
cd movies-series-backend

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Ejecutar migraciones
php artisan migrate

# Iniciar servidor
php artisan serve
```

## 🔑 Variables de Entorno

Configurar en `.env`:
```
APP_NAME="Movies & Series API"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=movies_series_db
DB_USERNAME=root
DB_PASSWORD=

TMDB_API_KEY=tu_api_key_aqui
```

## 📚 API Endpoints

### Autenticación
- `POST /api/register` - Registro de usuario
- `POST /api/login` - Inicio de sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/user` - Usuario actual

### Películas
- `GET /api/movies/popular` - Películas populares
- `GET /api/movies/top-rated` - Mejor valoradas
- `GET /api/movies/{id}` - Detalle de película
- `GET /api/movies/search?query=` - Buscar películas

### Series
- `GET /api/tv/popular` - Series populares
- `GET /api/tv/top-rated` - Mejor valoradas
- `GET /api/tv/{id}` - Detalle de serie

### Favoritos (requiere auth)
- `GET /api/favorites` - Listar favoritos
- `POST /api/favorites` - Agregar favorito
- `DELETE /api/favorites/{id}` - Eliminar favorito

## 🧪 Testing
```bash
php artisan test
```

## 📖 Documentación

En desarrollo...

## 👨‍💻 Desarrollo

**Fecha de inicio:** 10 de Noviembre 2025  
**Estado:** En desarrollo  
**Versión:** 1.0.0

---

Desarrollado con ❤️ usando Laravel
