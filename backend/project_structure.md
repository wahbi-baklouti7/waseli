# project_structure.md — Backend (Laravel 11)

---

## Folder Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   ├── Requests/
│   │   ├── Resources/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
│
├── database/
│   ├── migrations/
│   │   └── xxxx_create_carriers_table.php
│   └── seeders/
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── config/
│   └── cors.php
│
├── tests/
│   └── Feature/
│
├── .env
└── composer.json
```

---

## Layer Responsibilities

| Layer | File | Role | Contains logic |
|-------|------|------|---------------|
| Route | `routes/api.php` | Map URL to controller | ❌ |
| Middleware | `AdminKeyMiddleware` | Protect admin routes | ❌ |
| Request | `StoreCarrierRequest` | Validate input | ❌ |
| Controller | `CarrierController` | Receive → delegate → respond | ❌ |
| Service | `CarrierService` | All business logic | ✅ |
| Resource | `CarrierResource` | Shape JSON output | ❌ |
| Model | `Carrier` | Fillable, casts, scopes | ❌ minimal |

---

## Request Lifecycle

```
Incoming HTTP Request
        ↓
routes/api.php
        ↓
AdminKeyMiddleware (admin routes only)
        ↓
StoreCarrierRequest (POST routes only)
        ↓
CarrierController
        ↓
CarrierService
        ↓
Carrier Model → MySQL
        ↓
CarrierResource
        ↓
JSON Response
```

---

## Routes Map



---

# 🗄️ Database Schema (MVP)

## 📋 1. Users

| Column        | Type            | Description                  |
|--------------|-----------------|------------------------------|
| id           | BIGINT (PK)     | Unique user ID               |
| first_name         | VARCHAR         | User first name               |
| last_name         | VARCHAR         | User last name               |
| email        | VARCHAR UNIQUE  | User email                   |
| phone        | VARCHAR UNIQUE  | WhatsApp number              |
| password        | VARCHAR UNIQUE  | password              |
| residence_country        | BIGINT (FK)  | country(id)              |
| tunisian_city       | BIGINT (FK)   | region(id)              |
| is_traveler  | BOOLEAN         | Can act as traveler          |
| is_verified  | BOOLEAN         | WhatsApp verified            |
| trust_score  | INTEGER         | Reputation score             |
| status       | ENUM            | active / banned              |
| created_at   | TIMESTAMP       | Created time                 |
| updated_at   | TIMESTAMP       | Updated time                 |

---

---

## 🌍 2. Countries

| Column      | Type        | Description        |
|------------|------------|--------------------|
| id         | BIGINT (PK) | Country ID         |
| name       | VARCHAR     | Country name       |
| code       | VARCHAR     | Country code (ISO) |
| created_at | TIMESTAMP   | Created time       |

---

## 📍 3. Regions (Tunisia)

| Column      | Type        | Description                  |
|------------|------------|------------------------------|
| id         | BIGINT (PK) | Region ID                    |
| name       | VARCHAR     | Region name (Tunis, Sfax…)   |
| created_at | TIMESTAMP   | Created time                 |

---

## ✈️ 4. Trips

| Column             | Type        | Description                  |
|--------------------|------------|------------------------------|
| id                 | BIGINT (PK) | Trip ID                      |
| user_id            | BIGINT (FK) | Traveler (users.id)          |
| departure_country  | BIGINT (FK) | country(id)                  |
| arrival_city       | BIGINT (FK) | region(id)                   |
| arrival_date       | DATE        | Arrival date                 |
| notes              | TEXT        | Optional details             |
| status             | ENUM        | active / completed / cancelled |
| created_at         | TIMESTAMP   | Created time                 |
| updated_at         | TIMESTAMP   | Updated time                 |

---

## 📦 5. Contact Requests

| Column        | Type        | Description                          |
|--------------|------------|--------------------------------------|
| id           | BIGINT (PK) | Request ID                           |
| user_id      | BIGINT (FK) | Buyer (users.id)                     |
| traveler_id  | BIGINT (FK) | Traveler (users.id)                  |
| trip_id      | BIGINT (FK) | Related trip                         |
| message      | TEXT        | Request details                      |
| status       | ENUM        | pending / accepted / rejected / delivered / reviewed |
| created_at   | TIMESTAMP   | Created time                         |
| updated_at   | TIMESTAMP   | Updated time                         |

---

## ⭐ 6. Reviews

| Column        | Type        | Description                  |
|--------------|------------|------------------------------|
| id           | BIGINT (PK) | Review ID                    |
| request_id   | BIGINT (FK) | Related request              |
| reviewer_id  | BIGINT (FK) | User who reviewed            |
| traveler_id  | BIGINT (FK) | Traveler being reviewed      |
| rating       | INTEGER     | 1–5 stars                    |
| comment      | TEXT        | Optional feedback            |
| created_at   | TIMESTAMP   | Created time                 |

---

## 🔐 7. Verifications

| Column      | Type        | Description                  |
|------------|------------|------------------------------|
| id         | BIGINT (PK) | Verification ID              |
| user_id    | BIGINT (FK) | User                         |
| code       | VARCHAR     | Verification code            |
| status     | ENUM        | pending / verified           |
| created_at | TIMESTAMP   | Created time                 |

---

## 🚨 8. Flags (Optional)

| Column      | Type        | Description                  |
|------------|------------|------------------------------|
| id         | BIGINT (PK) | Flag ID                      |
| user_id    | BIGINT (FK) | Suspicious user              |
| reason     | TEXT        | Reason                       |
| created_by | BIGINT      | Admin ID                     |
| created_at | TIMESTAMP   | Created time                 |

---

# 🔗 Relationships

- users → trips (1:N)
- users → contact_requests (buyer & traveler)
- trips → contact_requests (1:N)
- contact_requests → reviews (1:1)
- users → reviews (1:N)

---

# 🔄 Core Flow

User → Request → Accepted → Delivered → Reviewed → Trust Score Updated




## Response Format

| Scenario | Status | Body |
|----------|--------|------|
| List carriers | 200 | CarrierResource collection |
| Create success | 201 | CarrierResource single |
| Validation fail | 422 | Laravel default errors |
| Unauthorized | 401 | `{ message }` |
| Not found | 404 | `{ message }` |
| Server error | 500 | `{ message }` |

---

## AI Rules

### Architecture

```
1.  Controllers only receive, delegate, and respond — zero logic
2.  All validation lives in Request classes only
3.  All business logic lives in Service classes only
4.  Models only define fillable, casts, scopes, relationships
5.  All responses go through Resource classes — never raw arrays
6.  All routes versioned under /api/v1/
7.  Admin routes always behind AdminKeyMiddleware
8.  Services injected via constructor — never instantiated with new
9.  Never use DB:: facade — always go through the Model
10. Never return raw exceptions — always return JSON with message
```

### Naming

```
Controllers  → PascalCase + Controller     CarrierController.php
Requests     → Verb + Model + Request      StoreCarrierRequest.php
Resources    → Model + Resource            CarrierResource.php
Services     → Model + Service             CarrierService.php
Models       → Singular PascalCase         Carrier.php
Middleware   → PascalCase + Middleware     AdminKeyMiddleware.php
Migrations   → snake_case descriptive      xxxx_create_carriers_table.php
```

### Code Style

```
1. Always use strict_types=1
2. Always type hint parameters and return types
3. Always use early return to avoid deep nesting
4. Controllers stay under 30 lines
5. Always use fillable — never use guarded
6. Always cast JSON columns in model $casts
```

---

## File Creation Order

```
1.  xxxx_create_carriers_table.php
2.  Carrier.php
3.  CarrierService.php
4.  StoreCarrierRequest.php
5.  CarrierResource.php
6.  AdminKeyMiddleware.php
7.  Api/CarrierController.php
8.  Admin/CarrierController.php
9.  routes/api.php
10. config/cors.php
11. CarrierSeeder.php
12. CarrierTest.php
```

---
