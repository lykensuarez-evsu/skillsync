# SkillSync - MySQL Database Setup Guide

## Prerequisites
- MySQL server running locally (XAMPP, WAMP, MAMP, or standalone)
- Web server (Apache/PHP) running
- Basic understanding of phpMyAdmin or MySQL CLI

## Setup Steps

### 1. Create the Database

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin in your browser (usually `http://localhost/phpmyadmin`)
2. Click "New" in the left sidebar
3. Enter database name: `skillsync`
4. Click "Create"
5. Select the `skillsync` database from the left panel
6. Click the "SQL" tab at the top
7. Copy and paste the contents of `database/setup.sql`
8. Click "Go" to execute

**Option B: Using MySQL CLI**
```bash
mysql -u root -p < database/setup.sql
```

### 2. Verify Database Connection

Edit `config/database.php` if needed:
```php
const DB_HOST = 'localhost';    // Your MySQL host
const DB_USER = 'root';         // Your MySQL username
const DB_PASSWORD = '';         // Your MySQL password
const DB_NAME = 'skillsync';    // Database name
```

### 3. Test the Connection

1. Make sure your PHP web server is running
2. Navigate to `http://localhost/skillsync` (adjust path as needed)
3. Open browser Developer Tools (F12) → Console
4. You should see: "SkillSync App Loaded"
5. The page should load student and internship data from the database

### 4. Verify Data in Database

You can check the data using phpMyAdmin:
1. Go to phpMyAdmin
2. Select `skillsync` database
3. Click on `students` table → see 3 sample students
4. Click on `internships` table → see 4 sample internships

## API Endpoints

The system uses these REST API endpoints:

### Students
- **GET** `/api/students.php?action=all` - Get all students
- **GET** `/api/students.php?action=single&id=1` - Get single student
- **POST** `/api/students.php` - Add new student
- **PUT** `/api/students.php` - Update student
- **DELETE** `/api/students.php?id=1` - Delete student

### Internships
- **GET** `/api/internships.php?action=all` - Get all internships
- **GET** `/api/internships.php?action=single&id=1` - Get single internship
- **POST** `/api/internships.php` - Add new internship
- **PUT** `/api/internships.php` - Update internship
- **DELETE** `/api/internships.php?id=1` - Delete internship

## Usage in App

1. **Load Page** → Data automatically fetches from database
2. **Add/Edit Records** → Data POSTs to API → Saved in database
3. **Delete Records** → API deletes from database
4. **All changes persist** → Data survives page reloads

## Troubleshooting

### "Connection refused" error
- Check if MySQL server is running
- Verify host/username/password in `config/database.php`

### "Table doesn't exist" error
- Run the SQL setup script: `database/setup.sql`
- Check that the database was created: `skillsync`

### Data not loading
- Open browser Console (F12)
- Check for API errors in Network tab
- Verify `api/` folder exists and contains `students.php` and `internships.php`
- Check server logs for PHP errors

### 403 Forbidden error
- Ensure file permissions are correct
- Check that Apache/PHP can access the files
- Verify `.htaccess` isn't blocking API requests

## Resetting Demo Data

To reset to original demo data:
1. Go to phpMyAdmin
2. Select `skillsync` database
3. Delete `students` and `internships` tables
4. Run `database/setup.sql` again

Or via MySQL CLI:
```bash
mysql -u root skillsync < database/setup.sql
```

## File Structure

```
skillsync/
├── config/
│   └── database.php           # Database connection config
├── api/
│   ├── students.php           # Student API endpoints
│   └── internships.php        # Internship API endpoints
├── database/
│   └── setup.sql              # Database schema + demo data
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js             # Now uses API calls instead of localStorage
├── includes/
│   ├── header.php
│   └── footer.php
└── index.php                  # Main entry point
```

## Next Steps

- **Production Deployment**: Add input validation and security measures
- **Authentication**: Add user login/logout system
- **Admin Panel**: Create dedicated admin section with more management features
- **Notifications**: Add success/error notifications for CRUD operations
