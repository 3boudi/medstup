### Use Case Documentation:

---

## USER USE CASES:

1. Register Account: The user creates an account with name, email, and password.
2. Login: The user logs into the system.
3. Browse Doctors: The user can browse a list of doctors based on specialization.
4. Send Consultation Request: The user sends a consultation request to a doctor.
5. View Request Status: The user can view the status of sent requests (pending/accepted/rejected).
6. Start Chat After Acceptance: The user can start chatting only if the doctor accepts the request.
7. Send Messages: The user can send messages (text, image, audio, PDF) to the doctor.
8. View Chat History: The user can view past chats.
9. Receive Notifications: The user receives system notifications (request updates, chat closed...).

---

## DOCTOR USE CASES:

1. Register Doctor Profile: The doctor registers with name, email, phone, and password.
2. Create Clinic Profile: The doctor sets up clinic information (name, location, description, optional photo).
3. Choose Specializations: The doctor selects the specializations they belong to.
4. Login: The doctor logs into the system.
5. View Incoming Requests: The doctor views received consultation requests.
6. Accept/Reject Request: The doctor accepts or rejects the request.
7. Start Chat After Acceptance: The doctor can chat only after accepting the request.
8. Send Messages: The doctor sends messages (text, image, voice, files).
9. Close Chat: The doctor can close the chat at any time.
10. Receive Notifications: The doctor receives alerts when new requests are received.

---

## ADMIN USE CASES:

1. Login: Admin logs into the system.
2. View Doctors: Admin sees all registered doctors.
3. Accept/Reject Doctors: Admin approves or rejects new doctor accounts.
4. Manage Specializations: Admin adds, edits, or deletes specializations.
5. View All Users: Admin views all registered users.
6. View Statistics: Admin checks system statistics (number of users, doctors, chats...).
7. Receive Notifications: Admin gets notified when a new doctor registers or when a user sends a consultation request.

---

## SYSTEM NOTIFICATIONS TABLE:

| Trigger                         | Notification Target | Content                                        |
| ------------------------------- | ------------------- | ---------------------------------------------- |
| User sends consultation request | Admin + Doctor      | "A new consultation request from User X"       |
| Doctor accepts/rejects request  | User                | "Your request was accepted/rejected by Dr. X"  |
| Doctor closes chat              | User                | "The chat has been closed by Dr. X"            |
| Doctor registers                | Admin               | "A new doctor has registered and needs review" |

---













# Medical Consultation Platform

A comprehensive Laravel-based API for connecting patients with doctors through online consultations and real-time chat functionality.

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [API Documentation](#api-documentation)
- [Database Schema](#database-schema)
- [Authentication](#authentication)
- [Real-time Features](#real-time-features)
- [File Uploads](#file-uploads)
- [Email Verification](#email-verification)
- [Admin Panel](#admin-panel)
- [Testing](#testing)
- [Contributing](#contributing)

## ✨ Features

### Core Functionality
- **Multi-role Authentication**: Users, Doctors, and Admins with separate authentication systems
- **Medical Specializations**: 37+ medical specialties with doctor categorization
- **Consultation Requests**: Patients can request consultations with specific doctors
- **Real-time Chat**: Live messaging between patients and doctors with file sharing
- **Doctor Approval System**: Admin-controlled doctor registration and approval
- **Email Verification**: Secure email verification for user accounts
- **Google OAuth**: Social login integration for users

### User Features
- Browse doctors by medical specialization
- View detailed doctor profiles and clinic information
- Request consultations with preferred doctors
- Real-time chat with approved doctors
- File and image sharing in chats
- Consultation history tracking

### Doctor Features
- Professional registration with clinic details
- Manage consultation requests (accept/reject)
- Real-time patient communication
- Chat management and closure
- Profile and specialization management

### Admin Features
- Doctor approval/rejection system
- Platform oversight and management
- User and doctor analytics
- System notifications

## 🛠 Technology Stack

- **Backend**: Laravel 12.x
- **Authentication**: Laravel Sanctum
- **Real-time**: Laravel Broadcasting with Pusher
- **Database**: SQLite (configurable)
- **Email**: Laravel Mail with custom templates
- **File Storage**: Laravel Storage
- **Social Auth**: Laravel Socialite (Google)
- **API**: RESTful API design

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (for broadcasting)
- SQLite or MySQL

### Step 1: Clone Repository
```bash
git clone <repository-url>
cd medical-consultation-platform
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Storage Setup
```bash
php artisan storage:link
```

### Step 6: Start Development Server
```bash
php artisan serve
```

## ⚙️ Configuration

### Environment Variables

Update your `.env` file with the following configurations:

#### Database
```env
DB_CONNECTION=sqlite
# For MySQL, uncomment and configure:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=medical_consultation
# DB_USERNAME=root
# DB_PASSWORD=
```

#### Google OAuth
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=your_redirect_uri
```

#### Broadcasting (for real-time chat)
```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_cluster
```

#### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## 📚 API Documentation

### Authentication Endpoints

#### User Authentication
```http
POST /api/user/register
POST /api/user/login
POST /api/user/logout
POST /api/user/login/google
```

#### Doctor Authentication
```http
POST /api/doctor/register
POST /api/doctor/login
POST /api/doctor/logout
```

#### Admin Authentication
```http
POST /api/admin/login
POST /api/admin/logout
```

### User Endpoints
```http
GET  /api/user/profile
GET  /api/user/specializations
GET  /api/user/specializations/{id}/doctors
GET  /api/user/doctors/{id}
POST /api/user/doctor/{id}/request-consultation
GET  /api/user/all-requests-consultation
GET  /api/user/chats
```

### Doctor Endpoints
```http
GET  /api/doctor/profile
GET  /api/doctor/consultation/pending
POST /api/doctor/consultation/{id}/respond
GET  /api/doctor/chats
POST /api/doctor/chat/{id}/close
```

### Admin Endpoints
```http
GET  /api/admin/profile
POST /api/admin/create
GET  /api/admin/pending
POST /api/admin/doctor/{id}/accept
POST /api/admin/doctor/{id}/reject
```

### Chat Endpoints
```http
GET  /api/chats/{chat}/messages
POST /api/chats/{chat}/send
```

### Request/Response Examples

#### User Registration
```json
POST /api/user/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Doctor Registration
```json
POST /api/doctor/register
{
    "name": "Dr. Jane Smith",
    "email": "jane@example.com",
    "phone": "+1234567890",
    "password": "password",
    "password_confirmation": "password",
    "clinic_name": "Smith Medical Center",
    "location": "New York, NY",
    "description": "General practice clinic",
    "specializations": [1, 3, 5]
}
```

#### Send Message
```json
POST /api/chats/{chat}/send
{
    "type": "text",
    "message": "Hello, how can I help you?",
    "file": null
}
```

## 🗄️ Database Schema

### Key Tables

#### Users
- `id`, `name`, `email`, `password`
- `email_verified_at`, `remember_token`

#### Doctors  
- `id`, `name`, `email`, `phone`, `password`
- `status` (pending/accepted/rejected)
- `clinic_id` (foreign key to clinics)

#### Specializations
- Pre-seeded with 37 medical specialties
- Many-to-many relationship with doctors

#### Consultation Requests
- Links users with doctors
- Status tracking (pending/accepted/rejected)
- Timestamp management

#### Chats & Messages
- Real-time messaging system
- File upload support
- Polymorphic sender relationships

## 🔐 Authentication

The platform uses **Laravel Sanctum** for API authentication with three separate guard systems:

### Custom Authentication Middlewares
- `AuthenticateUser`: Validates user tokens
- `AuthenticateDoctor`: Validates doctor tokens  
- `AuthenticateAdmin`: Validates admin tokens

### Token Management
```php
// Login response includes token
{
    "token": "1|abc123...",
    "user": {...},
    "message": "Login successful"
}

// Use token in Authorization header
Authorization: Bearer 1|abc123...
```

## 🔄 Real-time Features

### Broadcasting Setup
The platform uses Laravel Broadcasting for real-time chat functionality.

#### Event: MessageSent
```php
// Triggered when a message is sent
broadcast(new MessageSent($message))->toOthers();
```

#### Channel Authorization
```php
// Private channel for chat participants only
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Verify user is part of the chat
});
```

## 📁 File Uploads

### Supported File Types
- **Images**: JPEG, PNG (max 20MB)
- **Documents**: PDF (max 20MB)
- **Videos**: MP4 (max 20MB)

### Storage Configuration
```php
// Files stored in public/chat_files
'file' => 'nullable|file|mimes:jpeg,png,pdf,mp4|max:20480'
```

## 📧 Email Verification

### Custom Email Templates
- Located in `resources/views/emails/verify.blade.php`
- Styled verification emails with signed URLs
- 60-minute expiration for verification links

### Verification Flow
1. User registers → Verification email sent
2. User clicks link → Email verified
3. User can login with verified account

## 👑 Admin Panel

### Default Admin Account
```
Email: admin@system.com
Password: admin123
```

### Admin Capabilities
- View pending doctor registrations
- Approve/reject doctor applications
- Create additional admin accounts
- Monitor platform activity

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Database Testing
```bash
php artisan migrate:fresh --seed --env=testing
```

## 🚀 Deployment

### Production Checklist
- [ ] Configure production database
- [ ] Set up email service (SendGrid, SES, etc.)
- [ ] Configure Pusher for broadcasting
- [ ] Set up file storage (S3, etc.)
- [ ] Configure Google OAuth credentials
- [ ] Set `APP_ENV=production`
- [ ] Enable HTTPS
- [ ] Configure CORS for frontend

### Laravel Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🔧 Troubleshooting

### Common Issues

#### Broadcasting Not Working
```bash
# Install Pusher PHP SDK
composer require pusher/pusher-php-server

# Configure broadcasting driver
BROADCAST_CONNECTION=pusher
```

#### Email Verification Issues
- Ensure `MAIL_*` variables are configured
- Check signed URL configuration
- Verify route naming in `web.php`

#### File Upload Problems
- Run `php artisan storage:link`
- Check file permissions on storage directory
- Verify file size limits in php.ini

### Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 👨‍💻 Author

**AMIN HALITIM**
- Project Creator & Lead Developer
- Full Stack Developer specializing in Laravel & API Development

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Write descriptive commit messages
- Add tests for new features
- Update documentation

## 📄 License

This project is proprietary software developed by **AMIN HALITIM**. All rights reserved.

© 2024 AMIN HALITIM. This software and associated documentation files are the exclusive property of AMIN HALITIM. Unauthorized copying, distribution, or modification is strictly prohibited without explicit written permission from the author.

## 📞 Support

For support and questions:
- Contact: **AMIN HALITIM**
- Create an issue in the repository
- Check existing documentation
- Review Laravel documentation for framework-specific questions

---

**Built by AMIN HALITIM using**
