# Todo List API

A simple Todo List API built with Laravel 12 for Effective Mobile technical test

## 📋 Project Overview
This project demonstrates a robust Laravel-based Todo List API that implements clean architecture principles, featuring a clear separation of concerns between the repository layer for data access and the service layer for business logic. The application is thoroughly tested with a comprehensive suite of unit tests for individual components and feature tests for API endpoints, ensuring reliability and maintainability. Built with best practices in mind, it includes proper input validation, error handling, and follows RESTful conventions for all endpoints.


## Prerequisites

- PHP 8.2 or higher
- Composer
- SQLite

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/fatkulnurk/laravel-todo-list.git
   cd laravel-todo-list
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in `.env`:
   by default we use SQLite, so make sure you create "database.sqlite" file in the "database" directory
   
   on Linux or macOS use this command:
   ```bash
   touch database/database.sqlite
   ```
   
   on Windows use this command or create manual file:
   ```bash
   type nul > database/database.sqlite
   ```

6. Run migrations:
   ```bash
   php artisan migrate
   ```

## Running the Application

Start the development server:
```bash
php artisan serve
```

The API will be available at `http://localhost:8000` (depends on your configuration)

## Running Tests

```bash
php artisan test
```

## API Documentation

### Base URL
```
http://localhost:8000/api (depends on your configuration)
```

### Endpoints

#### Tasks

- **Get All Tasks**
  ```
  GET /api/tasks
  ```

- **Get Single Task**
  ```
  GET /api/tasks/{id}
  ```

- **Create Task**
  ```
  POST /api/tasks
  ```
  Request Body:
  ```json
  {
    "title": "Task title",
    "description": "Task description",
    "status": "pending" // pending, in-progress, completed (by default, if empty, value will be set to pending)
  }
  ```

- **Update Task**
  ```
  PUT /api/tasks/{id}
  ```
  Request Body:
  ```json
  {
    "title": "Updated title",
    "description": "Updated description",
    "status": "in-progress"
  }
  ```

- **Delete Task**
  ```
  DELETE /api/tasks/{id}
  ```

### Status Codes
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## Project Structure

```
app/
├── Http/
│   ├── Controllers/      # API Controllers
│   └── Resources/        # API Resources
├── Models/               # Eloquent Models
├── Repositories/         # Data access layer
│   ├── Contracts/        # Repository interfaces
│   └── Tasks/            # Task repository implementation
└── Services/             # Business logic
    ├── Contracts/        # Service interfaces
    └── Tasks/            # Task service implementation
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
