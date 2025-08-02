# Medical Consultation API

A comprehensive Laravel-based API for medical consultation services, featuring user authentication, doctor management, real-time chat, and consultation booking.

## Features

- **Multi-role Authentication**: Users, Doctors, and Admins with separate authentication systems
- **Real-time Chat**: WebSocket-based messaging between users and doctors
- **Consultation Management**: Request, approve, and manage medical consultations
- **File Sharing**: Secure file upload and sharing in chats
- **Email Verification**: Secure email verification for user accounts
- **Admin Panel**: Doctor approval and system management
- **Health Monitoring**: Built-in health check endpoints
- **API Documentation**: Comprehensive API documentation

## Requirements

- PHP 8.2+
- Laravel 12.0+
- MySQL 8.0+ or PostgreSQL 13+
- Redis (for caching and sessions)
- Node.js 18+ (for frontend assets)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd medical-consultation-api
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database and other services in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medical_consultation
DB_USERNAME=your_username
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### 4. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage Setup

```bash
php artisan storage:link
```

### 6. Start the Application

```bash
# Start the Laravel server
php artisan serve

# Start the queue worker (in another terminal)
php artisan queue:work

# Start Reverb for real-time features (in another terminal)
php artisan reverb:start

# Compile frontend assets (if needed)
npm run dev
```

## API Endpoints

### Authentication

#### User Authentication
- `POST /api/user/register` - Register new user
- `POST /api/user/login` - User login
- `POST /api/user/login/google` - Google OAuth login
- `POST /api/user/logout` - User logout

#### Doctor Authentication
- `POST /api/doctor/register` - Register new doctor (requires admin approval)
- `POST /api/doctor/login` - Doctor login
- `POST /api/doctor/logout` - Doctor logout

#### Admin Authentication
- `POST /api/admin/login` - Admin login
- `POST /api/admin/logout` - Admin logout

### User Endpoints
- `GET /api/user/profile` - Get user profile
- `GET /api/user/specializations` - List all specializations
- `GET /api/user/specializations/{id}/doctors` - Get doctors by specialization
- `GET /api/user/doctors/{id}` - Get doctor details
- `POST /api/user/doctor/{id}/request-consultation` - Request consultation
- `GET /api/user/all-requests-consultation` - Get user's consultation requests
- `GET /api/user/chats` - Get user's chats
- `GET /api/user/chats/{chatId}/messages` - Get chat messages
- `POST /api/user/chats/{chatId}/send` - Send message

### Doctor Endpoints
- `GET /api/doctor/profile` - Get doctor profile
- `GET /api/doctor/consultation/pending` - Get pending consultation requests
- `POST /api/doctor/consultation/{id}/respond` - Accept/reject consultation
- `GET /api/doctor/chats` - Get doctor's chats
- `GET /api/doctor/chats/{chatId}/messages` - Get chat messages
- `POST /api/doctor/chats/{chatId}/send` - Send message
- `POST /api/doctor/chats/{chatId}/close` - Close chat

### Admin Endpoints
- `GET /api/admin/profile` - Get admin profile
- `POST /api/admin/create` - Create new admin
- `GET /api/admin/pending` - Get pending doctor registrations
- `POST /api/admin/doctor/{id}/accept` - Accept doctor registration
- `POST /api/admin/doctor/{id}/reject` - Reject doctor registration

### Health Check
- `GET /api/health` - Basic health check
- `GET /api/health/detailed` - Detailed system health check

## Security Features

- **Rate Limiting**: API endpoints are rate-limited to prevent abuse
- **Input Validation**: Comprehensive input validation and sanitization
- **File Upload Security**: Secure file upload with type and size validation
- **Token-based Authentication**: Laravel Sanctum for API authentication
- **CORS Configuration**: Proper CORS setup for frontend integration
- **SQL Injection Protection**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Input sanitization prevents XSS attacks

## File Upload

The API supports secure file uploads in chat messages:

- **Allowed Types**: JPEG, PNG, GIF, PDF, DOC, DOCX
- **Maximum Size**: 10MB per file
- **Security**: Files are renamed with unique identifiers
- **Storage**: Files are stored in `storage/app/public/chat_files`

## Real-time Features

The application uses Laravel Reverb for real-time messaging:

```bash
# Start Reverb server
php artisan reverb:start
```

WebSocket events:
- `MessageSent`: Broadcasted when a new message is sent

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## Production Deployment

### 1. Server Requirements

- PHP 8.2+ with required extensions
- Web server (Nginx/Apache)
- MySQL/PostgreSQL database
- Redis server
- SSL certificate

### 2. Environment Setup

```bash
# Copy production environment file
cp .env.production .env

# Generate application key
php artisan key:generate

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 3. Database Migration

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 4. File Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. Queue Worker Setup

Set up a process manager like Supervisor to keep queue workers running:

```ini
[program:medical-api-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/app/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/worker.log
```

### 6. Web Server Configuration

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Monitoring

The application includes built-in health check endpoints:

- `/api/health` - Basic health status
- `/api/health/detailed` - Detailed system checks including database, cache, storage, and queue status

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team or create an issue in the repository.
