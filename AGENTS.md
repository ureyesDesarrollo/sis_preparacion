# Base de datos

El proyecto utiliza MySQL.

Base:
sis_preparacion

Puedes consultar la estructura de la base usando:

./scripts/db-query.sh "SQL"

Ejemplos:

./scripts/db-query.sh "SHOW TABLES"

./scripts/db-query.sh "DESCRIBE procesos"

./scripts/db-query.sh "SHOW CREATE TABLE procesos"

## Reglas

- La conexión es de solo lectura.
- Nunca propongas DROP, TRUNCATE o DELETE sin que el usuario lo solicite.
- Antes de generar una consulta compleja, revisa las tablas involucradas.
- No asumas nombres de columnas.
- Usa SHOW CREATE TABLE o DESCRIBE cuando necesites conocer la estructura.
- Para consultas complejas utiliza EXPLAIN antes de recomendar índices.
- La versión de producción del sistema es legacy PHP + MySQL.