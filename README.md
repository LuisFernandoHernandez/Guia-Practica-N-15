# 📋 TaskBoard - Semana 15
---

📅 Avances e Implementaciones: Semana 15

En la Semana 15 se desarrolló la capa completa de servicios API RESTful, incorporando autenticación segura mediante tokens, estandarización de respuestas en JSON, pruebas automatizadas y pruebas de colección en Bruno API Client:

Autenticación con Laravel Sanctum (API Tokens):

Creación de `AuthApiController` para gestionar los flujos de Registro (`/api/register`) y Login (`/api/login`), retornando un token de acceso `Bearer`.

Protección de endpoints de comercios y transacciones exigiendo cabecera `Authorization: Bearer <TOKEN>`.

Controladores API y Validaciones Específicas:
- Implementación de `TransaccionApiController` para gestionar la creación y listado de transacciones vía API.
- Uso de Form Requests (`RegistroRequest`, `GuardarTransaccionRequest`) para separar las reglas de validación de la lógica de los controladores.
- Estandarización con API Resources:
- Creación de `ComercioResource` y `TransaccionResource` para dar un formato JSON limpio, uniforme y seguro a las respuestas de la API.

Pruebas y Colección HTTP con Bruno API Client:
- Configuración y consumo de la colección de endpoints con el cliente de APIs Bruno, permitiendo autenticación fluida mediante Bearer Tokens.
- Disponibilidad del archivo local `pruebas-api.http` para pruebas directas en entornos de desarrollo.
- Pruebas Automatizadas (Feature Tests):
- Cobertura de pruebas en `tests/Feature/ApiTaskBoardTest.php` para validar la autenticación, la creación de transacciones y el control de acceso.


---

## ⚙️ Archivos Omitidos (Optimizaciones de Peso)

Para permitir la subida directa a GitHub sin sobrepasar el número máximo de archivos, eliminé los siguientes elementos no esenciales del archivo:

* 🚫 **`vendor/`**: Directorio de librerías de PHP (ocupa +20 MB). Se restaura mediante Composer.
* 🚫 **`.env`**: Archivo de variables de entorno locales (contiene claves de seguridad). Se restaura a partir de `.env.example`.
* 🚫 **`storage/framework/views/*` y `cache/`**: Vistas precompiladas y archivos de sesión/caché temporal. Se regeneran automáticamente al ejecutar el proyecto.
* 🚫 **`database/database.sqlite`**: Base de datos local. Se genera nuevamente ejecutando las migraciones y seeders.
