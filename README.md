# Laravel Blog Application

A feature-rich blog platform built with Laravel 12, featuring role-based access control, social authentication, user activity logging, and a comprehensive admin dashboard.

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [User Roles](#user-roles)
- [Testing](#testing)
- [Project Structure](#project-structure)
- [Technologies Used](#technologies-used)

## Features

### 🔐 Authentication & Authorization

- **User Registration & Login**: Traditional email/password authentication
- **Social Authentication**: Login with Google and Facebook via Laravel Socialite
- **Role-Based Access Control**: Using Spatie Laravel Permission package
  - **Admin Role**: Full system access, user management, and admin dashboard
  - **User Role**: Can create, edit, and delete their own posts and comments
- **Session Management**: Secure session handling with Laravel Sanctum
- **Rate Limiting**: Protection against brute-force login attempts

### 📝 Post Management

- **CRUD Operations**: Create, Read, Update, and Delete blog posts
- **Public Posts View**: Anyone can view published posts (no authentication required)
- **My Posts**: Authenticated users can manage their own posts
- **Soft Deletes**: Posts are soft-deleted and can be restored by admins
- **Pagination**: Posts are paginated (10 posts per page)
- **Query Optimization**: Eager loading to prevent N+1 queries
- **Caching**: Posts are cached for improved performance (1-hour cache)
- **Authorization**: Policy-based authorization for post operations

### 💬 Comments System

- **Nested Comments**: Support for parent-child comment relationships
- **Comment Management**: Users can add and delete their own comments
- **Real-time Display**: Comments are displayed with the post
- **Soft Deletes**: Comments can be soft-deleted
- **Authorization**: Policy-based authorization for comment operations

### 👥 Admin Dashboard

- **Statistics Overview**:
  - Total users count
  - Total posts count
  - Total comments count
  - Recent user activities
- **User Management**:
  - View all users
  - Edit user roles (assign admin/user roles)
  - Delete users
  - View user's posts
- **Post Management**:
  - Delete any post
  - Restore soft-deleted posts
  - View all posts across the system

### 📊 Activity Logging

- **User Activity Tracking**: Comprehensive logging of user actions
- **Separate Log Channel**: `user_activity` channel for better organization
- **Logged Information**:
  - User details (ID, name, email, role)
  - Request details (method, URL, path, route name)
  - IP address and user agent
  - HTTP status codes
  - Request data (excluding sensitive information)
- **Specific Action Logging**: Special logs for CRUD operations on posts and comments
- **Unauthorized Access Tracking**: Logs failed authentication and authorization attempts
- **Log Location**: `storage/logs/user-activities/`

### 🛡️ Security Features

- **CSRF Protection**: Built-in Laravel CSRF protection
- **Password Hashing**: Secure password hashing using bcrypt
- **Authorization Policies**: Policy-based authorization for posts and comments
- **Role-Based Middleware**: Custom middleware for role verification
- **User Authentication Middleware**: Ensures users are authenticated before accessing protected routes
- **Input Validation**: Form request validation for all user inputs
- **SQL Injection Prevention**: Using Eloquent ORM

### 🎨 User Interface

- **Responsive Design**: Mobile-friendly interface
- **Blade Templates**: Server-side rendering with Laravel Blade
- **Component-Based Layout**: Reusable layout components
- **Flash Messages**: Success/error messages for user actions
- **Intuitive Navigation**: Easy-to-use navigation system

## Requirements

- PHP >= 8.2
- Composer
- MySQL/PostgreSQL/SQLite (SQLite configured by default)
- Git

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/khanallama/blog.git
cd blog
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install
```

### 3. Environment Setup

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit `.env` file and configure your database settings:

```env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=blog
# DB_USERNAME=root
# DB_PASSWORD=
```

### 5. Social Authentication Configuration (Optional)

To enable Google and Facebook login, add the following to your `.env` file:

```env
# Google OAuth
GOOGLE_CLIENT_ID=google_client_id
GOOGLE_CLIENT_SECRET=google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=facebook_client_id
FACEBOOK_CLIENT_SECRET=facebook_client_secret
FACEBOOK_REDIRECT_URI=http://127.0.0.1:8000/auth/facebook/callback
```

### 6. Run Migrations

```bash
# Run database migrations
php artisan migrate
```

### 7. Seed Database (Optional)

```bash
# Seed roles and create an admin user
php artisan db:seed --class=RoleAndAdminSeeder

# Default admin credentials:
# Email: admin@example.com
# Password: password
```

### 8. Build Assets

```bash
# Build frontend assets for development
npm run dev

# Or build for production
npm run build
```

### 9. Start the Development Server

```bash
# Start Laravel development server
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

## Quick Setup (Alternative)

If you prefer a single command setup:

```bash
composer setup
```

This will:
- Install Composer dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run migrations
- Install NPM dependencies
- Build assets

## Configuration

### Cache Configuration

The application uses caching for improved performance. To clear the cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Queue Configuration (Optional)

For background job processing:

```bash
# Start queue worker
php artisan queue:listen --tries=1
```

## Usage

### Creating a Regular User Account

1. Visit the homepage
2. Click "Register"
3. Fill in your details (name, email, password)
4. After registration, you'll be logged in automatically

### Creating an Admin Account

Run the seeder to create a default admin account:

```bash
php artisan db:seed --class=RoleAndAdminSeeder
```

Or manually assign admin role to a user:

```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin');
```

### Writing a Blog Post

1. Log in to your account
2. Navigate to "Create Post"
3. Fill in the title and body
4. Click "Create Post"

### Managing Posts

- **View All Posts**: Visit the homepage
- **My Posts**: Click "My Posts" to see only your posts
- **Edit Post**: Click the edit button on your post
- **Delete Post**: Click the delete button on your post

### Adding Comments

1. Open any blog post
2. Scroll to the comments section
3. Write your comment
4. Click "Post Comment"

### Admin Functions

**Access Admin Dashboard**: Navigate to `/admin/dashboard`

**User Management**:
- View all users: `/admin/users`
- Edit user role: Click edit on any user
- Delete user: Click delete button
- View user's posts: Click "View Posts" on any user

**Post Management**:
- Delete any post from the admin dashboard
- Restore soft-deleted posts

## User Roles

### Guest (Unauthenticated)
- View all published posts
- View individual post details
- View comments
- Cannot create, edit, or delete content

### User (Authenticated Regular User)
- All guest permissions
- Create new posts
- Edit own posts
- Delete own posts
- Add comments to any post
- Delete own comments
- View "My Posts" page

### Admin
- All user permissions
- Access admin dashboard
- View system statistics
- Manage all users
- Assign/revoke roles
- Delete users
- Delete any post
- Restore soft-deleted posts
- Delete any comment

## Testing

The application includes comprehensive tests covering:
- Unit tests for models and middleware
- Feature tests for controllers
- Authentication tests
- Role and permission tests

### Running Tests

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test file
php artisan test --filter PostControllerTest

# Run with detailed output
php artisan test --verbose
```

## Technologies Used

### Backend
- **Laravel 12**: PHP framework
- **PHP 8.2+**: Programming language
- **Spatie Laravel Permission**: Role and permission management
- **Laravel Socialite**: Social authentication (Google, Facebook)
- **Laravel Sanctum**: API authentication

### Frontend
- **Blade**: Templating engine
- **Bootstrap 5**: CSS framework

### Database
- **MySQL**: (can use SQLite/PostgreSQL)
- **Eloquent ORM**: Database interaction

### Testing
- **PHPUnit**: Testing framework
- **Laravel Testing**: Built-in testing utilities

### Development Tools
- **Composer**: PHP dependency manager
- **Laravel Pint**: Code style fixer
- **Laravel Pail**: Log viewer

## Key Features Implementation Details

### Caching Strategy
- Post index pages are cached for 1 hour
- Individual posts with comments are cached
- Cache is automatically cleared when posts are updated or deleted (via observers)

### N+1 Query Prevention
- Eager loading of relationships (`with()` method)
- `withCount()` for comment counts
- Optimized queries in all controller methods

### Soft Deletes
- Posts and comments use soft deletes
- Admin can restore soft-deleted posts
- Deleted data is preserved for potential recovery

### Logging System
- Custom log channel for user activities
- Structured JSON logging
- Automatic cleaning of sensitive data
- Daily log rotation

### Security Best Practices
- CSRF token validation
- XSS prevention via Blade escaping
- SQL injection prevention via Eloquent
- Password hashing with bcrypt
- Rate limiting on login attempts
- Secure session management
