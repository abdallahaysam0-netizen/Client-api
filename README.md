# Client Management System (Full Stack)

A comprehensive management system featuring a Laravel backend and a React/Vite frontend. Designed for efficiency, security, and ease of use.

## 🚀 Features

- **Robust Authentication**: Secure login and session management.
- **Client Management**: Full CRUD operations for managing client data.
- **Note System**: Attach detailed notes to clients for better tracking.
- **Attachment Handling**: Securely upload and manage documents related to clients.
- **Role-Based Access Control (RBAC)**: Fine-grained permissions using Spatie Laravel-Permission.
- **System Health Monitoring**: Real-time health checks and performance monitoring.
- **Automated Testing**: Comprehensive feature tests for both backend and frontend reliability.

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12
- **Database**: MySQL / SQLite (Development)
- **Security**: Spatie Permissions
- **Broadcasting**: Laravel Reverb

### Frontend
- **Library**: React.js
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **Networking**: Axios & Laravel Echo

## 📦 Installation

### Prerequisites
- PHP 8.2+
- Node.js & NPM
- Composer

### Backend Setup (laravel/)
1. Navigate to the backend directory:
   ```bash
   cd backend
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start the server:
   ```bash
   php artisan serve
   ```

### Frontend Setup (frontend/)
1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

## 🧪 Testing
Run backend tests using:
```bash
php artisan test
```

## 📄 License
This project is open-sourced under the MIT license.
