
# Laravel Posts REST API

A Laravel REST API practice project for managing posts, tags, and comments.

The application demonstrates API authentication, authorization, input validation, Eloquent relationships, pagination, database transactions, and automated feature testing.

## Technologies

- PHP 8.4
- Laravel 13
- Laravel Sanctum
- MySQL (Development)
- SQLite (Testing)
- PHPUnit
- Laravel Eloquent ORM

## Features

- User registration and login
- Token-based authentication using Laravel Sanctum
- User logout and token revocation
- Create, retrieve, update, and delete posts
- Post ownership authorization using policies
- Attach tags to posts
- Filter posts
- Add comments to posts
- Retrieve comments with author information
- Comment pagination
- Eager loading to reduce N+1 queries
- Database transactions for related writes
- Automated API feature tests

## Installation

### 1. Install dependencies

```bash
composer install
```

### 2. Configure environment

Copy `.env.example` to `.env`.

For Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 3. Configure MySQL

Update the following values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Create the database before running migrations.

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Run the application

The project can be accessed through Laravel Herd.

Alternatively:

```bash
php artisan serve
```

## Authentication

This API uses Laravel Sanctum personal access tokens.

First register or log in using the appropriate endpoint.

For protected endpoints, include:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

Logout revokes the current personal access token.

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | /api/register | Register user |
| POST | /api/login | Login |
| POST | /api/logout | Logout |
| GET | /api/me | Authenticated user details |
| GET | /api/posts | List posts |
| POST | /api/posts | Create post |
| GET | /api/posts/{post} | Retrieve post |
| PUT/PATCH | /api/posts/{post} | Update post |
| DELETE | /api/posts/{post} | Delete post |
| POST | /api/posts/{post}/comments | Create comment |
| GET | /api/posts/{post}/comments | List comments |

The Laravel application also includes `/api/user`.


## Post Filtering and Pagination

The Posts API supports filtering using query parameters.

### Available Filters

| Parameter | Description | Example |
|---|---|---|
| published | Filter by publication status | ?published=1 |
| user_id | Filter posts by author | ?user_id=2 |
| tag_id | Filter posts by tag | ?tag_id=3 |
| page | Select pagination page | ?page=2 |

### Examples

Retrieve published posts:

```http
GET /api/posts?published=1
```

Retrieve posts belonging to a specific author:

```http
GET /api/posts?user_id=2
```

Retrieve posts containing a specific tag:

```http
GET /api/posts?tag_id=3
```

Combine multiple filters:

```http
GET /api/posts?published=1&tag_id=3&page=2
```

### Pagination

The API returns up to 10 posts per page.

Pagination responses include data, navigation links, and metadata.

The `withQueryString()` method preserves active filters when generating pagination links.

### Query Optimization

The Posts API uses Eloquent eager loading to retrieve author information efficiently:

```php
->with('user:id,name')
```

It also retrieves comment counts without loading every comment:

```php
->withCount('comments')
```


### Database Transactions

Post creation and tag synchronization are performed inside database transactions.

This prevents partial database updates if an operation fails.


## Authorization

The API uses Laravel Policies to protect post operations.

Authenticated users can create posts.

Only a post's author can update or delete it.

Unauthorized modifications return HTTP 403.

Unauthenticated access to protected endpoints returns HTTP 401.

## Eloquent Relationships

The application demonstrates:

- User has many Posts
- Post belongs to User
- Post has many Comments
- Comment belongs to User
- Post belongs to many Tags

Eager loading is used to avoid unnecessary database queries.

Comments are returned newest first, with 10 comments per page.

## Automated Testing

The project uses PHPUnit for automated tests.

The testing environment uses SQLite.

Run all tests:

```bash
php artisan test
```

Run only Comments API tests:

```bash
php artisan test --filter=CommentApiTest
```

The test suite covers authentication, authorization, validation, post operations, relationships, pagination, and missing records.

## HTTP Response Codes

| Status | Meaning |
|---|---|
| 200 | Successful request |
| 201 | Resource created |
| 204 | Successful deletion |
| 401 | Unauthenticated |
| 403 | Forbidden |
| 404 | Resource not found |
| 422 | Validation error |

## Project Purpose

This project was developed to practise Laravel REST API development and demonstrate understanding of API security, Eloquent ORM, database operations, and automated testing.
