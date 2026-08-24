# Entorno de Desarrollo - sis_preparacion

## Descripción

Este proyecto utiliza:

- PHP 7.4
- Apache
- MySQL 8
- Docker Engine
- Docker Compose
- VS Code
- Xdebug 3

El objetivo es que cualquier desarrollador pueda levantar el proyecto y depurarlo localmente sin instalar PHP, Apache o MySQL directamente en el sistema operativo.

---

# Requisitos

## Software requerido

- Docker Engine
- Docker Compose
- Visual Studio Code

## Extensiones VS Code recomendadas

- PHP Intelephense
- PHP Debug
- Docker
- SQLTools
- SQLTools MySQL Driver
- GitLens
- Error Lens
- Better Comments
- Todo Tree

---

# Levantar el proyecto

## Construir contenedores

```bash
docker compose build
```

## Iniciar servicios

```bash
docker compose up -d
```

## Verificar contenedores

```bash
docker ps
```

---

# Detener servicios

```bash
docker compose down
```

---

# Reconstruir completamente

```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```

---

# Acceder al contenedor PHP

```bash
docker exec -it sis_preparacion-app-1 bash
```

---

# Verificar versión PHP

```bash
php -v
```

---

# Verificar módulos PHP

```bash
php -m
```

---

# Verificar Xdebug

```bash
php -m | grep xdebug
```

---

# Base de datos

## Datos de conexión

Host:

```text
localhost
```

Puerto:

```text
3306
```

Usuario:

```text
root
```

Password:

```text
root
```

Base de datos:

```text
bd_sis_preparacion
```

---

# Configuración de Xdebug

Archivo:

```text
docker/xdebug.ini
```

Configuración:

```ini
zend_extension=xdebug

xdebug.mode=debug

xdebug.start_with_request=yes

xdebug.discover_client_host=1

xdebug.client_port=9003

xdebug.idekey=VSCODE
```

---

# Configuración VS Code

Archivo:

```text
.vscode/launch.json
```

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "PHP Docker Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            }
        }
    ]
}
```

---

# Uso de depuración

## Iniciar depuración

1. Abrir VS Code.
2. Ir a "Run and Debug".
3. Seleccionar "PHP Docker Xdebug".
4. Presionar F5.
5. Colocar breakpoints.
6. Abrir la aplicación en el navegador.

---

# Convención de comentarios

Utilizar Better Comments para identificar rápidamente elementos importantes.

## Pendientes

```php
// TODO: Implementar validación de inventario
```

## Correcciones

```php
// FIX: Corregir duplicidad de embarques
```

## Revisiones

```php
// REVIEW: Revisar algoritmo de parcialización
```

## Consultas SQL

```php
// SQL: Consulta principal de existencias
```

## Reglas críticas

```php
// ! NO MODIFICAR SIN VALIDACIÓN DE NEGOCIO
```

## Preguntas técnicas

```php
// ? ¿Qué ocurre si el inventario ya fue consumido?
```

---

# Comandos útiles

## Ver logs

```bash
docker compose logs -f
```

## Reiniciar aplicación

```bash
docker compose restart app
```

## Reiniciar MySQL

```bash
docker compose restart db
```

## Estado de servicios

```bash
docker compose ps
```

---

# Buenas prácticas

- No utilizar `var_dump()` en código productivo.
- Utilizar Xdebug para análisis y seguimiento.
- Documentar reglas de negocio complejas.
- Mantener consultas SQL comentadas.
- Utilizar commits pequeños y descriptivos.
- No subir credenciales al repositorio.
- Mantener Dockerfile y docker-compose actualizados.

---

# Estructura recomendada de comentarios

```php
// =====================================================
// EMBARQUES
// =====================================================

// TODO: Validar peso máximo permitido

// SQL: Consulta principal de inventario

// REVIEW: Revisar lógica de parcialización

// ! REGLA DE NEGOCIO CRÍTICA
```