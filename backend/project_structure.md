# project_structure.md — Backend (Laravel 13)

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

### 🧠 1. Overview

The system operates as a **two-sided logistics marketplace** connecting carriers and buyers:

*   **🚚 Carriers**: Create and manage **Trips** (routes they are traveling).
*   **👤 Buyers**:
    *   **Apply to Trips**: Express interest in an existing carrier's trip (`trip_requests`).
    *   **Post Delivery Needs**: Create requests for items they need delivered (`delivery_requests`).
*   **🔁 Matching**: Carriers can also apply to buyer-created requests (`request_applications`).

---

### 🧱 2. Database Schema

#### 👤 Users & Locations
| Table | Field | Type/Constraint | Description |
| :--- | :--- | :--- | :--- |
| **users** | `id` | PK | Unique identifier |
| | `first_name` | string | |
| | `last_name` | string | |
| | `email` | string (unique) | |
| | `phone` | string | |
| | `role` | enum | `buyer`, `carrier` |
| | `resident_country_id`| FK → countries.id | |
| | `region_id` | FK → regions.id | |
| | `email_confirmed_at` | timestamp | |
| | `is_verified` | boolean | |
| | `is_whatsapp_verified`| boolean | |
| **countries** | `id` | PK | |
| | `name` | string | |
| **regions** | `id` | PK | |
| | `name` | string | |
| | `country_id` | FK → countries.id | |

#### 📦 Catalog & Logistics
| Table | Field | Type/Constraint | Description |
| :--- | :--- | :--- | :--- |
| **categories** | `id` | PK | Item categories (e.g., Electronics, Food) |
| | `name` | string | |
| **trips** | `id` | PK | |
| | `carrier_id` | FK → users.id | The carrier performing the trip |
| | `departed_country_id`| FK → countries.id | Starting point |
| | `arrival_city_id` | FK → regions.id | Destination |
| | `arrival_date` | date | Estimated arrival |
| | `status` | enum | `open`, `in_progress`, `completed` |

#### 🤝 Marketplace Transactions
| Table | Field | Type/Constraint | Description |
| :--- | :--- | :--- | :--- |
| **delivery_requests** | `id` | PK | Buyer-posted delivery needs |
| | `buyer_id` | FK → users.id | |
| | `arrival_city_id` | FK → regions.id | |
| | `category_id` | FK → categories.id | |
| | `date` | date | Desired delivery date |
| **trip_requests** | `id` | PK | **Core Table**: Applications from buyers to trips |
| | `trip_id` | FK → trips.id | |
| | `buyer_id` | FK → users.id | |
| | `status` | enum | `pending`, `accepted`, `rejected` |
| | `delivery_code` | string | Secure code for delivery verification |
| | `package_status` | enum | `waiting`, `picked_up`, `delivered` |
| **request_applications**| `id` | PK | Applications from carriers to delivery requests |
| | `request_id` | FK → delivery_requests.id| |
| | `carrier_id` | FK → users.id | |
| | `status` | enum | `pending`, `accepted`, `rejected` |

---

### 🔗 3. Relationships

#### 👤 User Relations
*   **One User** has many **Trips** (as Carrier).
*   **One User** has many **Delivery Requests** (as Buyer).
*   **One User** has many **Trip Requests** (as Buyer applied to a Trip).
*   **One User** has many **Request Applications** (as Carrier applied to a Delivery Request).

#### 🚚 Logistics Relations
*   **Trip**:
    *   Belongs to one **Carrier** (User).
    *   Has many **Trip Requests**.
    *   Belongs to one **Departure Country** and one **Arrival Region**.
*   **Delivery Request**:
    *   Belongs to one **Buyer** (User).
    *   Belongs to one **Region** and one **Category**.
    *   Has many **Request Applications**.

#### 🤝 Transaction Relations
*   **Trip Request**: Link between one **Trip** and one **Buyer**.
    > [!NOTE]
    > Each row represents "One package/service instance within a trip".
*   **Request Application**: Link between one **Delivery Request** and one **Carrier**.

---

### ⚙️ 4. Core Business Logic

#### 🔵 Flow 1: Buyer → Trip (Apply to existing trip)
1.  **Apply**: Buyer browsing trips → applies to a specific **Trip**.
2.  **Request**: `trip_requests` record created (Status: `pending`).
3.  **Approve**: Carrier reviews and accepts the request (Status: `accepted`).
4.  **Confirm**: System generates a `delivery_code`.
5.  **Deliver**: Carrier updates `package_status` (waiting → picked_up → delivered).

#### 🟢 Flow 2: Carrier → Request (Bid on delivery need)
1.  **Bid**: Carrier browsing delivery requests → applies to a specific **Delivery Request**.
2.  **Application**: `request_applications` record created (Status: `pending`).
3.  **Approve**: Buyer reviews and accepts the bid (Status: `accepted`).
4.  **Execute**: Fulfillment process begins.

---

### 📊 5. State System

| Entity | Field | State Flow |
| :--- | :--- | :--- |
| **Trip Request** | `status` | `pending` ➔ `accepted` OR `rejected` |
| **Trip Request** | `package_status` | `waiting` ➔ `picked_up` ➔ `delivered` |
| **Trip** | `status` | `open` ➔ `in_progress` ➔ `completed` |





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
