# ⚽ Balonazo

Sistema web y API REST hecho en **Laravel** y **MySQL** para llevar el control completo de un complejo de canchas deportivas. Nació para reemplazar las libretas, los mensajes perdidos y los enredos de horarios duplicados por una plataforma centralizada donde los jugadores reservan rápido y recepción lleva la caja al centavo.

---

## ¿Cómo funciona el negocio?

El sistema está pensado para operar un establecimiento con **6 canchas**, alquiler de utilería y cobros en efectivo directo en el lobby:

* **Las Canchas:**
  * **2 Canchas Normales** ($15/hr) para partidos casuales.
  * **3 Canchas Pro** ($20/hr) con mejor césped e iluminación.
  * **1 Cancha Premium** ($30/hr) que incluye de cortesía **1 balón de Alta Calidad gratis** durante el partido.
* **Alquiler de Utilería:** Si a alguien le falta equipo antes de jugar, puede sumar a su reserva balones normales ($3), balones de alta calidad ($5), guantes de portero ($5) o tacos en tallas 7, 8 y 9 ($5). El sistema descuenta el stock disponible por horario para no prestar de más.
* **Cuidado del equipo (Multas):** Si en el partido pinchan un balón, recepción puede cargar la penalización directo a la cuenta ($20 si era balón normal o $30 si era de alta calidad).
* **Caja en el Lobby:** No hay pasarelas de pago complicadas; todo se liquida en efectivo en recepción y queda registrado para sacar reportes filtrados por fecha e imprimir el corte del día.

---

## Cuentas para probarlo rápido

Al correr los seeders se crean dos usuarios listos para entrar y probar ambos lados del sistema:

* **Recepción / Admin:** `admin@balonazo.com` — contraseña: `admin123`
* **Jugador / Cliente:** `jugador@balonazo.com` — contraseña: `cliente123`

---

## Levantar el proyecto en tu PC

Si vas a correrlo localmente, asegúrate de tener **PHP**, **Composer**, **Node.js** y **MySQL** encendidos:

```bash
# 1. Clonar el repo y entrar a la carpeta
git clone https://github.com
cd Sistema-Gestion-Canchas

# 2. Instalar dependencias de PHP y frontend
composer install
npm install && npm run build

# 3. Configurar entorno (crea una BD llamada "balonazo" en MySQL)
cp .env.example .env
php artisan key:generate

# 4. Crear tablas, cargar datos iniciales y arrancar
php artisan migrate --seed
php artisan serve
```

Luego abre `http://localhost:8000` en tu navegador y listo.

---

## Rutas de la API (`/api`)

Además de la web, el sistema expone una API protegida con **Laravel Sanctum** por si más adelante se conecta una app móvil:

* **Públicas:** `POST /api/register`, `POST /api/login`, `GET /api/canchas`, `GET /api/equipamientos` y `GET /api/disponibilidad` (pasando `fecha`, `hora_inicio` y `hora_fin`).
* **Jugador (con Token):** `GET /api/mis-reservas` y `POST /api/reservaciones`.
* **Lobby / Admin (con Token):** `GET /api/admin/reservaciones`, `PATCH /api/admin/reservaciones/{id}/estado`, `POST /api/admin/reservaciones/{id}/penalizacion` y `POST /api/admin/reservaciones/{id}/cobrar`.
