# Blog Management System

A full-stack web application built with Laravel (backend) and Quasar/Vue.js (frontend) that provides blog management functionality.

## Features

-   User authentication with Laravel Passport
-   Blog management (Create, Read, Update, Delete)
-   Blog status management (Publish/Hide)
-   Blog search functionality
-   Blog preview
-   Soft delete (Archive) functionality

## Technologies Used

### Backend

-   Laravel 10.x
-   Laravel Passport for API authentication
-   MySQL database

### Frontend

-   Quasar Framework (Vue.js 3)
-   Axios for API communication
-   Vue Router for routing
-   Quasar UI components

## Installation

### Prerequisites

-   PHP 8.1 or higher
-   Composer
-   Node.js and npm
-   MySQL
-   Git

### Backend Setup

1. Clone the repository:

```bash
git clone https://github.com/yourusername/blog-management-system.git
cd blog-management-system
```

2. Install PHP dependencies:

```bash
composer install
```

3. Copy the environment file and configure your database:

```bash
cp .env.example .env
# Edit .env file with your database credentials
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Run migrations:

```bash
php artisan migrate
```

6. Install Passport:

```bash
php artisan passport:install
```

### Frontend Setup

1. Navigate to the frontend directory:

```bash
cd frontend
```

2. Install JavaScript dependencies:

```bash
npm install
```

3. Start the development server:

```bash
npm run dev
```

## Usage

1. Access the Laravel API at: `http://localhost:8000`
2. Access the Quasar frontend at: `http://localhost:8080`

## API Endpoints

### Authentication

-   POST `/api/register` - Register a new user
-   POST `/api/login` - Login a user
-   POST `/api/logout` - Logout a user (requires authentication)

### Blog Management

-   GET `/api/blogs` - List all blogs (with optional search parameter)
-   POST `/api/blogs` - Create a new blog
-   GET `/api/blogs/{id}` - Get a specific blog
-   PUT `/api/blogs/{id}` - Update a blog
-   PATCH `/api/blogs/{id}/status` - Update blog status
-   DELETE `/api/blogs/{id}` - Archive a blog (soft delete)

## License

[MIT](https://choosealicense.com/licenses/mit/)
