# Kikundi Authentication Setup

## ✅ Installation Complete

All authentication components have been successfully installed and configured:

1. **Laravel Sanctum** - Installed and configured
2. **AuthController** - Created with login/logout/register endpoints
3. **User Seeder** - Created with test accounts
4. **API Routes** - All routes protected with `auth:sanctum` middleware
5. **Web Routes** - All routes protected with `auth` middleware
6. **User Model** - Updated with HasApiTokens trait
7. **Security** - All sensitive endpoints require authentication

## 🔐 Test Credentials

Use these credentials to login:

**Admin Account:**
- Email: `admin@kikundi.com`
- Password: `password`

**Demo Account:**
- Email: `demo@kikundi.com`
- Password: `password`

## 🚀 How to Test

### 1. Start the Development Server

```bash
php artisan serve
```

### 2. Access the Application

Open your browser and go to: `http://localhost:8000`

### 3. Login

Use one of the test credentials above to login.

## 📡 API Endpoints

### Public Endpoints
- `POST /api/login` - Login with email and password
- `POST /api/register` - Register new user
- `GET /api/health` - Health check

### Protected Endpoints (Require Authentication)

All endpoints below require authentication via Laravel Sanctum (Bearer token or session).

**User Management:**
- `POST /api/logout` - Logout
- `GET /api/user` - Get current user

**Members:**
- `GET /api/members` - Get all members
- `POST /api/members` - Create new member
- `GET /api/members/statistics` - Get member statistics
- `GET /api/members/{id}` - Get member details
- `PUT /api/members/{id}` - Update member
- `DELETE /api/members/{id}` - Delete member
- `POST /api/members/{id}/check-eligibility` - Check loan eligibility

**Investments:**
- `GET /api/investments` - Get all investments
- `POST /api/investments` - Create new investment
- `GET /api/investments/statistics` - Get investment statistics
- `GET /api/investments/member/{memberId}` - Get total investments by member
- `GET /api/investments/{id}` - Get investment details
- `PUT /api/investments/{id}` - Update investment
- `DELETE /api/investments/{id}` - Delete investment

**Loans:**
- `GET /api/loans` - Get all loans
- `POST /api/loans` - Create new loan
- `GET /api/loans/statistics` - Get loan statistics
- `GET /api/loans/overdue` - Get overdue loans
- `GET /api/loans/{id}` - Get loan details
- `PUT /api/loans/{id}` - Update loan
- `DELETE /api/loans/{id}` - Delete loan

**Repayments:**
- `GET /api/repayments` - Get all repayments
- `POST /api/repayments` - Create new repayment
- `GET /api/repayments/statistics` - Get repayment statistics
- `GET /api/repayments/loan/{loanId}` - Get repayments by loan
- `GET /api/repayments/{id}` - Get repayment details
- `PUT /api/repayments/{id}` - Update repayment
- `DELETE /api/repayments/{id}` - Delete repayment

**Reports:**
- `GET /api/reports/dashboard` - Get dashboard data
- `GET /api/reports/quarterly` - Get quarterly report
- `GET /api/reports/biannual` - Get biannual report
- `GET /api/reports/annual` - Get annual report
- `GET /api/reports/custom` - Get custom report
- `GET /api/reports/member/{memberId}/statement` - Get member statement

**Settings:**
- `GET /api/settings` - Get all settings
- `POST /api/settings` - Create new setting
- `GET /api/settings/all` - Get all settings (alternative)
- `POST /api/settings/bulk-update` - Update multiple settings
- `GET /api/settings/{key}` - Get specific setting
- `PUT /api/settings/{key}` - Update specific setting

## 🔧 Troubleshooting

### If login fails:

1. **Clear application cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

2. **Regenerate application key:**
```bash
php artisan key:generate
```

3. **Check .env file has:**
```env
SESSION_DRIVER=database  # or file
SESSION_LIFETIME=120
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:8000,127.0.0.1,127.0.0.1:8000
```

4. **Run migrations again if needed:**
```bash
php artisan migrate:fresh --seed
```

### If you get CSRF token errors:

Make sure your `.env` file has:
```env
APP_URL=http://localhost:8000
SESSION_DOMAIN=localhost
```

## 📝 How Authentication Works

1. **User submits login form** on `/` (welcome page)
2. **JavaScript sends POST request** to `/api/login`
3. **AuthController validates credentials** and creates:
   - Session cookie for web authentication
   - API token for API authentication
4. **User is redirected** to `/dashboard`
5. **All subsequent requests** use the session cookie automatically

## 🎯 Next Steps

- Access the dashboard at `/dashboard`
- Manage members at `/members`
- Track investments at `/investments`
- Manage loans at `/loans`
- Record repayments at `/repayments`
- View reports at `/reports`
- Configure settings at `/settings`

All these routes are now protected and require authentication!
