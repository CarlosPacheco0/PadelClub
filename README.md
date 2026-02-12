# 🏓 Proyecto Cancha Pádel

Aplicación desarrollada con **Laravel** para la gestión de canchas de pádel (usuarios, reservas, configuración, etc.).

---

## 📋 Requisitos del sistema

Antes de instalar el proyecto, asegúrate de tener instalado:

* **PHP >= 8.1**
* **Composer**
* **MySQL / MariaDB** (u otro motor compatible con Laravel)
* **Node.js >= 18** y **npm**
* **Git**

Extensiones PHP recomendadas:

* OpenSSL
* PDO
* Mbstring
* Tokenizer
* XML
* Ctype
* Fileinfo

---

## 🚀 Proceso de instalación

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
```

---

### 2️⃣ Instalar dependencias de PHP

```bash
composer install
```

---

### 3️⃣ Instalar dependencias de frontend

```bash
npm install
```

---

### 4️⃣ Configurar variables de entorno

Copia el archivo de ejemplo:

```bash
cp .env.example .env
```

Configura en el archivo `.env`:

```env
APP_NAME=CanchaPadel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=clave
```

---

### 6️⃣ Configurar la base de datos

1. Crea la base de datos en tu motor (MySQL, MariaDB, etc.).
2. Verifica que las credenciales del `.env` sean correctas.

---

## 🗄️ Migraciones y seeders

### Ejecutar migraciones

Las migraciones crean la estructura de la base de datos:

```bash
php artisan migrate
```

Si deseas recrear todo desde cero:

```bash
php artisan migrate:fresh
```

---

### Ejecutar seeders (opcional)

Si el proyecto incluye datos iniciales:

```bash
php artisan db:seed
```

O todo junto:

```bash
php artisan migrate --seed
```

---

## 🖥️ Compilar assets

Para entorno de desarrollo:

```bash
npm run dev
```

Para producción:

```bash
npm run build
```

---

## ▶️ Ejecutar el proyecto

```bash
php artisan serve
```

Accede desde el navegador:

```
http://127.0.0.1:8000
```

---

## 📂 Estructura importante

* `app/` → Lógica de la aplicación
* `database/migrations/` → Migraciones
* `database/seeders/` → Datos iniciales
* `resources/` → Vistas y assets
* `routes/` → Rutas del sistema

---

## 🔐 Seguridad

* El archivo `.env` **NO debe subirse al repositorio**
* Usar `.env.example` como referencia
* No exponer credenciales reales

---

## 🛠️ Comandos útiles

```bash
php artisan migrate:fresh --seed
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

---

## 📌 Notas adicionales

* Asegúrate de que las carpetas `storage/` y `bootstrap/cache/` tengan permisos de escritura.
* En entornos Linux:

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📄 Licencia

Este proyecto es de uso interno / privado.
