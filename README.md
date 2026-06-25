# Bagamoyo Municipal Management System

A comprehensive Laravel-based web application for storing and managing Bagamoyo municipal information regarding all business licenses, fishery, markets, and business frames.

## Features

### Core Modules
1. **Dashboard** - Statistical overview with Chart.js charts showing expired/active licenses, revenue, fishery data
2. **License Register** - Business license management with auto-expiry calculation (mid-year = 6 months, annual = 1 year)
3. **Fishery Register** - Fishermen and fishing boats registration
4. **Markets Register** - Markets and cages/vizimba management
5. **Business Frames/Vibanda** - Business frames with rent tracking

### System Settings
- **User Management** - Role-based access with Spatie Permission
- **Location Management** - Pre-defined regions, districts, wards, villages
- **Business Settings** - License categories and revenue sources
- **Mobile App Backend** - Advertisements and opportunities management

### Reports
- License reports with export (PDF, Excel, CSV)
- Expired licenses tracking
- Fishery statistics
- Market and cage reports
- Business frame utilization
- Map distribution with coordinates

### Notifications
- SMS reminders: 21, 14, 7 days before expiry, 1 day after
- Custom SMS to single or all business owners
- Hygiene reminders for environmental cleanliness

### Activity Logging
- All user actions logged for inspection
- Searchable and filterable logs

## Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Bootstrap 5, Chart.js
- **Database**: MySQL 8.0
- **Auth**: Laravel Auth + Spatie Permission
- **DataTables**: yajra/laravel-datatables-oracle
- **SMS**: Twilio/Beem/Tanzania gateways (configurable)

## Tanzania Flag Colors Used
- Green: #1E9048 (primary)
- Yellow/Gold: #FFC400 (accent)
- Black: #1C1C1C (sidebar)
- Blue: #1DA1D4 (info)

## Installation

1. Clone repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Run `php artisan key:generate`
5. Create MySQL database: `bagamoyo_municipal`
6. Run `php artisan migrate --seed`
7. Run `php artisan serve`

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@bagamoyo.go.tz | password123 |
| Manager | manager@bagamoyo.go.tz | password123 |
| Staff | staff@bagamoyo.go.tz | password123 |

## Scheduled Commands

Daily at 08:00 - Automatic SMS reminders for expiring licenses:
- 21 days before expiry
- 14 days before expiry
- 7 days before expiry
- 1 day after expiry

## API Endpoints (Mobile App)

- `GET /api/opportunities` - List opportunities
- `GET /api/advertisements` - List advertisements
- `POST /api/advertisements/subscribe` - Subscribe to advertise
- `POST /api/register` & `/api/login` - Authentication

## License

MIT License - Tanzania Government
