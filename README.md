# Symfony Admin Panel

A complete admin panel built with Symfony framework for managing database records with full CRUD operations.

## Features

- **List View**: Display all records in a responsive table with pagination
- **Create**: Add new records with form validation
- **Edit**: Modify existing records
- **Delete**: Remove records with confirmation dialog
- **View**: Display detailed record information
- **Responsive Design**: Bootstrap-based UI that works on all devices
- **Form Validation**: Server-side validation with user-friendly error messages
- **Flash Messages**: Success and error notifications
- **CSRF Protection**: Secure forms with CSRF tokens

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB or PostgreSQL
- Web server (Apache/Nginx)

## Installation

1. **Install dependencies:**
   ```bash
   composer install
   ```

2. **Configure database:**
   - Copy `.env` to `.env.local`
   - Update the `DATABASE_URL` in `.env.local` with your database credentials

3. **Create database and run migrations:**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

4. **Start the development server:**
   ```bash
   symfony server:start
   # or
   php -S localhost:8000 -t public/
   ```

5. **Access the admin panel:**
   Open your browser and go to `http://localhost:8000/admin`

## Project Structure

```
├── config/                 # Configuration files
├── migrations/             # Database migrations
├── public/                 # Web root directory
├── src/
│   ├── Controller/         # Controllers
│   ├── Entity/            # Doctrine entities
│   ├── Form/              # Symfony forms
│   └── Kernel.php         # Application kernel
├── templates/             # Twig templates
│   ├── admin/            # Admin panel templates
│   └── base.html.twig    # Base layout
├── .env                  # Environment variables
└── composer.json         # Dependencies
```

## Customization

### Modifying the Entity

The current entity (`TableRecord`) has 5 string fields. To customize it based on your table structure:

1. **Update the Entity** (`src/Entity/TableRecord.php`):
   - Modify field names and types
   - Add or remove fields as needed
   - Update validation constraints

2. **Update the Form** (`src/Form/TableRecordType.php`):
   - Add/remove form fields
   - Modify field types and options
   - Update validation rules

3. **Update Templates**:
   - Modify `templates/admin/index.html.twig` for the list view
   - Update `templates/admin/create.html.twig` and `templates/admin/edit.html.twig` for forms
   - Adjust `templates/admin/view.html.twig` for the detail view

4. **Create New Migration**:
   ```bash
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   ```

### Adding New Features

- **Search/Filter**: Add search functionality to the list view
- **Pagination**: Implement pagination for large datasets
- **Export**: Add CSV/Excel export functionality
- **Authentication**: Add user login and role-based access
- **File Upload**: Add file upload capabilities

## Database Schema

The default table structure includes:

- `id` (Primary Key, Auto Increment)
- `field1` (VARCHAR 255, Required)
- `field2` (VARCHAR 255, Required)
- `field3` (VARCHAR 255, Optional)
- `field4` (VARCHAR 255, Optional)
- `field5` (VARCHAR 255, Optional)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

## API Endpoints

- `GET /admin` - List all records
- `GET /admin/create` - Show create form
- `POST /admin/create` - Create new record
- `GET /admin/view/{id}` - View record details
- `GET /admin/edit/{id}` - Show edit form
- `POST /admin/edit/{id}` - Update record
- `POST /admin/delete/{id}` - Delete record

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.
