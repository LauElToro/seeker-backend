# 🅿️ Seeker Parking - Backend (Laravel 12 + MySQL + Docker)

Este proyecto implementa una API RESTful para gestionar parkings y encontrar el parking más cercano a una ubicación geográfica.  
Desarrollado en **Laravel 12**, utilizando **MySQL**, **Docker** y **Docker Compose**.  
Incluye **tests E2E** que validan toda la funcionalidad.

---

## **📌 Requisitos**

- [Docker](https://www.docker.com/) y [Docker Compose](https://docs.docker.com/compose/) instalados.
- Puerto `8000` libre en tu máquina (para la API).
- Puerto `3307` libre en tu máquina (para la base de datos MySQL).

---

## **🚀 Instalación y ejecución**

1. Cloná el repositorio:
   ```bash
   git clone <URL_DEL_REPO>
   cd seeker-backend
   ```

2. Levantá los contenedores con Docker:
   ```bash
   docker-compose up --build -d
   ```

3. Verificá que el contenedor esté corriendo:
   ```bash
   docker ps
   ```

4. Accedé a la API en tu navegador o con `curl`:
   ```
   http://localhost:8000/api/parkings
   ```

---

## **🛠️ Endpoints disponibles**

### 1. **Listar todos los parkings**
```http
GET /api/parkings
```

**Ejemplo:**
```bash
curl -X GET http://localhost:8000/api/parkings
```

---

### 2. **Crear un nuevo parking**
```http
POST /api/parkings
```

**Body (JSON):**
```json
{
  "name": "Parking Centro",
  "address": "Av. Corrientes 123",
  "latitude": -34.6037,
  "longitude": -58.3816
}
```

**Ejemplo:**
```bash
curl -X POST http://localhost:8000/api/parkings   -H "Content-Type: application/json"   -d '{"name":"Parking Centro","address":"Av. Corrientes 123","latitude":-34.6037,"longitude":-58.3816}'
```

---

### 3. **Obtener un parking por ID**
```http
GET /api/parkings/{id}
```

**Ejemplo:**
```bash
curl -X GET http://localhost:8000/api/parkings/1
```

---

### 4. **Buscar el parking más cercano**
```http
POST /api/parking/nearest
```

**Body (JSON):**
```json
{
  "latitude": -34.6037,
  "longitude": -58.3816
}
```

**Respuesta:**
```json
{
  "parking": {
    "id": 1,
    "name": "Parking Centro",
    "address": "Av. Corrientes 123",
    "latitude": -34.6037,
    "longitude": -58.3816
  },
  "distance_meters": 180,
  "exceeds_500": false
}
```

> Si la distancia es **mayor a 500 metros**, se logueará en la tabla `request_logs`.

---

## **🧪 Correr los tests**

El proyecto incluye **tests E2E (end-to-end)** que cubren:
- Creación de parkings.
- Listado.
- Búsqueda del parking más cercano.
- Validaciones de payloads inválidos.
- Logs de solicitudes fuera de rango.

1. Crear base de datos de testing:
   ```bash
   docker exec -it seeker_db mysql -uroot -psecret -e "CREATE DATABASE IF NOT EXISTS seeker_test;"
   ```

2. Ejecutar los tests:
   ```bash
   docker exec -it seeker_app php artisan test --testdox
   ```

**Salida esperada:**
```
PASS  Tests\Feature\ParkingApiTest
✓ e2e crea lista muestra nearest y loguea fuera de rango
✓ valida payloads invalidos
✓ devuelve 404 si no existe el parking

Tests:    3 passed (18 assertions)
Duration: 6.4s
```

---

## **📂 Estructura del proyecto**

```
seeker-backend/
├── app/
│   ├── Http/Controllers/ParkingController.php
│   ├── Models/Parking.php
│   └── Models/RequestLog.php
├── database/migrations/
│   ├── create_parkings_table.php
│   └── create_request_logs_table.php
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
│   ├── Feature/ParkingApiTest.php
│   └── TestCase.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

---

## **📜 Observaciones**

- El proyecto utiliza **MySQL** en contenedor `db` (credenciales definidas en `.env`).
- La base de datos de testing es **`seeker_test`**, mientras que la productiva es **`seeker`**.
- Si querés reiniciar todo desde cero:
  ```bash
  docker-compose down -v
  docker-compose up --build -d
  ```

---

## **👨‍💻 Autor**

**Lautaro Figueroa** - _Desarrollador Fullstack_  
📧 [lautyfigueroalau@gmail.com](mailto:lautyfigueroalau@gmail.com)
