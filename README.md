# Laravel Booking Management API with Swagger (OpenAPI) Documentation

This project is a Laravel-based RESTful API with integrated Swagger (OpenAPI) documentation and Bearer Token authentication (JWT or Sanctum). Swagger UI provides a friendly interface to explore, test, and interact with the API.

---

## 🚀 Local Development Setup

### 1. Clone the Repository

```bash
git clone https://github.com/govindamandal/sws-event-booking-api.git
cd sws-event-booking-api
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Copy .env and Configure
```bash
cp .env.example .env
php artisan key:generate
```

Edit .env and configure your DB connection and APP_URL (default: http://localhost:8000):
```bash
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Serve the Project
```bash
php artisan serve
```
The Laravel Booking Management API app will now be available at: http://localhost:8000

## 📚 Swagger Documentation

This project uses __Swagger UI__ to generate interactive API documentation.

### 🔧 Swagger Setup
Swagger annotations are added using __OpenAPI 3.0__ and Laravel-compatible tools like `zircote/swagger-php` and `L5Swagger`.

### Generate Swagger Docs
```bash
php artisan l5-swagger:generate
```

This will generate Swagger JSON at `storage/api-docs/api-docs.json`.

### 📖 Access Swagger UI

Once the docs are generated, visit: http://localhost:8000/api/documentation, This will open the Swagger UI where you can view and test all available API endpoints.

## 🔐 Authentication (Bearer Token)

This API uses __Bearer Token (JWT or Sanctum)__ for protected routes.

### Add Authorization Header in Swagger UI

1. Login to the Auth Endpoint `/api/login`

This will return the response like 

```json
{
  "user": {
    "id": 1,
    "name": "Govinda Mandal",
    "email": "govinda4india@gmail.com",
    "email_verified_at": null,
    "created_at": "2025-04-09T09:02:53.000000Z",
    "updated_at": "2025-04-09T09:02:53.000000Z"
  },
  "token": "7|84OvT11aqpwxJbozfa4cxxwFI4fGzdaPdCxuODSh61675a91"
}
```

2. Copy the token value, in this case `7|84OvT11aqpwxJbozfa4cxxwFI4fGzdaPdCxuODSh61675a91`
3. Click the __Authorize__ button in Swagger UI.
4. In the “Value” field, enter token copied (`7|84OvT11aqpwxJbozfa4cxxwFI4fGzdaPdCxuODSh61675a91`)
5. Click “Authorize” and then “Close”.

## 🧪 Sample Authentication Flow

### 1. Register a new user
```bash
POST /api/register
```
Sample body:
```json
{
  "name": "Govinda Mandal",
  "email": "govinda4india@gmail.com",
  "password": "Govinda@123",
  "password_confirmation": "Govinda@123"
}
```

### 2. Login to get Token

```bash
POST /api/login
```
Sample body
```json
{
  "email": "govinda4india@gmail.com",
  "password": "Govinda@123"
}
```
Response:
```json
{
  "user": {
    "id": 1,
    "name": "Govinda Mandal",
    "email": "govinda4india@gmail.com",
    "email_verified_at": null,
    "created_at": "2025-04-09T09:02:53.000000Z",
    "updated_at": "2025-04-09T09:02:53.000000Z"
  },
  "token": "3|8dfGxzuZFTmdk4W6hsYQD4OhV6rsxzdpAK9hNl5cb00846b3"
}
```

## 🔄 Regenerating Swagger Docs After Any Code Changes

```bash
php artisan l5-swagger:generate
```
## 📦 Useful Commands

	•	Serve app: `php artisan serve`
	•	Generate Swagger docs: `php artisan l5-swagger:generate`
	•	Clear config: `php artisan config:clear`

## 🛠️ Technologies Used
	•	Laravel 10+
	•	L5-Swagger (OpenAPI 3)
	•	Sanctum or JWT for authentication
	•	MySQL or any DB of your choice

