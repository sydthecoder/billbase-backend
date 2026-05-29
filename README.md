## Backend (Laravel) Overview

This backend is a Laravel API organized into feature modules under `app/Modules`. Each module owns its routes, controllers, requests, services, and resources, keeping feature logic self-contained and consistent across the codebase.
zDRRYVEYfZ0rNx8JP09y
## Project Structure

```
app/
	Http/
		Controllers/       # Shared controllers (if any)
		Middleware/
	Models/              # Eloquent models
	Modules/             # Feature modules (Auth, Customers, Invoices, etc.)
		Customers/
			Controllers/
			Requests/
			Resources/
			Services/
			Routes.php
	Providers/
	Services/            # Shared services (e.g., code generators, PDF helpers)
config/                # App and package configuration
database/
	migrations/           # Schema migrations
	seeders/              # Database seeders
routes/
	api.php               # API entry point; includes module routes
	web.php
	console.php
tests/                  # Feature and unit tests
```

## Module Pattern

Each module is structured the same way:

- `Routes.php` registers the module endpoints and middleware.
- `Controllers/` handle HTTP requests and delegate to services.
- `Requests/` define validation rules.
- `Services/` contain the business logic.
- `Resources/` shape API responses.

The main API entry point (`routes/api.php`) requires each module's `Routes.php` file to register routes.

## Example: Customers Module

**Routing**

Customers routes are registered in `app/Modules/Customers/Routes.php` and protected with `auth:sanctum` middleware.

```
GET    /api/v1/customers
POST   /api/v1/customers
GET    /api/v1/customers/{id}
PUT    /api/v1/customers/{id}
DELETE /api/v1/customers/{id}
```

**Request flow**

`CustomerController` receives the request, uses a `CreateCustomerRequest` or `UpdateCustomerRequest` for validation, and then delegates to `CustomerService`. Responses are returned using `CustomerResource`.

**Create customer example**

Request:

```http
POST /api/v1/customers
Authorization: Bearer <token>
Content-Type: application/json
```

```json
{
	"customer_type": "individual",
	"first_name": "Ada",
	"last_name": "Lovelace",
	"email": "ada@example.com",
	"phone": "+27 12 345 6789",
	"street_address": "123 Example St",
	"city": "Cape Town",
	"province": "WC",
	"postal_code": "8000",
	"status": "active"
}
```

Response (shape):

```json
{
	"status": "success",
	"message": "Customer created.",
	"data": {
		"id": 1,
		"customer_code": "CUS-00001",
		"customer_type": "individual",
		"full_name": "Ada Lovelace",
		"email": "ada@example.com",
		"address": {
			"street_address": "123 Example St",
			"city": "Cape Town",
			"province": "WC",
			"postal_code": "8000"
		},
		"status": "active",
		"created_at": "2026-05-22T12:00:00Z"
	}
}
```

Notes:

- The organization is inferred from the authenticated user (`auth()->user()`), so `organization_id` is set server-side.
- `customer_code` is generated via `App\Services\CodeGeneratorService`.
- Emails must be unique per organization; duplicates return a 422 response.
