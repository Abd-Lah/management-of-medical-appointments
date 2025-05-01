# 🏥 Doctor-Patient Appointment API

A RESTful backend API built with Laravel that facilitates **reservation and appointment management** between doctors and patients. It includes **authentication**, **role-based access control**, and **calendar/time slot management**.

---

## 🚀 Features

- **Authentication** using Laravel Sanctum
- **Role & Permission Management** using Laratrust
- **Doctor & Patient Profiles**
- **Calendar Management** for Doctors
- **Appointment Booking** for Patients
- **Search Doctors** by city or specialty
- **Rating & Reviews** system
- **Multi-device Session Management**

---

## 🗄️ Database Schema Overview

### Users Table

| Field              | Type     | Description                          |
|-------------------|----------|--------------------------------------|
| id                | bigint   | Primary key                          |
| nom, prenom       | string   | User full name                       |
| email, password   | string   | Auth credentials                     |
| specialite        | string   | Doctor specialization (nullable)     |
| ville             | string   | City (nullable)                      |
| cabinet_adresse   | string   | Office address (nullable)            |
| registercomerce   | string   | Commercial registry (nullable)       |
| tele, tele_cabinet| string   | Phone numbers                        |
| description       | longText | Doctor bio (nullable)                |
| prix              | float    | Consultation price (nullable)        |
| score             | float    | Review average (nullable)            |
| nombre_reservations| int     | Reservation count (nullable)         |
| status            | boolean  | Activation status                    |
| photo             | string   | Profile image (nullable)             |

### Roles & Permissions

- Handled via **Laratrust**
- Tables:
    - `roles`, `permissions`
    - `role_user`, `permission_user`, `permission_role`

### Reservations Table

| Field         | Type         | Description                         |
|---------------|--------------|-------------------------------------|
| doctor_id     | foreign key  | Reference to doctor (user)          |
| patient_id    | foreign key  | Reference to patient (user)         |
| title         | string       | Title of reservation                |
| date          | date         | Reservation date                    |
| start_date    | datetime     | Start time                          |
| end_date      | datetime     | End time                            |
| comment       | text         | Optional patient comments           |
| review        | float        | Optional doctor review              |
| status        | boolean      | Reservation status                  |

### Work Times Table

- Linked to `users` table via `doctor_id`
- Includes working days and time slots as JSON

---

## 🔐 Authentication

- Uses **Laravel Sanctum** for API token-based authentication
- Supports multi-device login/logout
- Email verification (optional support)

---

## 🔄 API Routes

### Public Routes (Unauthenticated)

- `POST /register` — Register a user
- `POST /login` — Login

### Authenticated Routes

- **Device Management**
    - `GET /show/connected/device`
    - `DELETE /logout`
    - `DELETE /logout/{id}`
    - `DELETE /logout/all`

- **Profile**
    - `GET /profil`
    - `PUT /profil/password`
    - `PUT /profil/informations`
    - `PUT /profil/informations/profissionels` *(doctor only)*
    - `POST /profil/photo`
    - `DELETE /profil/photo`

### Owner (Admin)

- `GET /owner/acceuil`
- `GET /demendes` — List of pending doctors
- `POST /doctors/search`
- `GET/POST/PUT/DELETE /doctors` — Doctor management
- `GET/POST/PUT/DELETE /roles`
- `GET/POST/PUT/DELETE /permissions`

### Doctor

- `GET/POST /doctor/calendar`
- `PUT /doctor/calendar` — Update availability
- `GET /reservations` — All reservations
- `POST /reservations/search`
- `POST /reservations/validation/{id}`
- `GET /calendar` — Events on calendar

### Patient

- `GET /patient/acceuil`
- `POST /medcin/search`
- `GET /medcin/show/{id}`
- `GET/POST/PUT/DELETE /patient/Rendez_Vous`

---

## 🧰 Tech Stack

- Laravel 8
- Laravel Sanctum
- Laratrust (Roles & Permissions)
- MySQL (or any SQL-compatible DB)
- PHP 8.0.2

---

## 🧪 Installation
   ```bash
    git clone git@github.com:Abd-Lah/management-of-medical-appointments.git
    cd management-of-medical-appointments
    composer install
    cp .env.example .env
    php artisan key:generate
    ### Configure DB credentials in .env and create the database
    php artisan migrate --seed
    php artisan serve

