# SkillSync - Quick Start Guide (MySQL Implementation)

## What Changed?

Your SkillSync application has been **fully migrated from localStorage to MySQL database**. Here's what was done:

### Changes Made:

1. **Database Layer** (`config/database.php`)
   - MySQL connection configuration
   - Handles all database connectivity

2. **API Endpoints** 
   - `api/students.php` - Full CRUD operations for students
   - `api/internships.php` - Full CRUD operations for internships
   - All endpoints return JSON responses

3. **JavaScript Updates** (`assets/js/app.js`)
   - Replaced `localStorage` calls with `fetch()` API calls
   - All data operations now go through REST endpoints
   - Data automatically persists in database

4. **Database Schema** (`database/setup.sql`)
   - Students table with demo data
   - Internships table with demo data
   - Auto-increment primary keys
   - Proper indexes for performance

5. **Updated Footer** (`includes/footer.php`)
   - Removed localStorage data injection
   - Now loads all data from API

---

## Setup Instructions (5 Minutes)

### Step 1: Ensure MySQL is Running
```bash
# Check if MySQL is running
mysql --version

# If using XAMPP/WAMP, start Apache and MySQL
```

### Step 2: Run Database Setup
**Method A: phpMyAdmin (Easiest)**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "SQL" tab
3. Copy all content from `database/setup.sql`
4. Paste into SQL editor and click "Go"

**Method B: Command Line**
```bash
mysql -u root -p < database/setup.sql
```

### Step 3: Update Config (if needed)
Edit `config/database.php`:
```php
const DB_HOST = 'localhost';      // MySQL host
const DB_USER = 'root';           // MySQL username
const DB_PASSWORD = '';           // MySQL password (usually empty for local)
const DB_NAME = 'skillsync';      // Database name
```

### Step 4: Test
1. Navigate to your SkillSync URL
2. Check browser Console (F12) - should see: "SkillSync initialized with MySQL database backend"
3. Data should load from database automatically

---

## Architecture Overview

```
User Interface (HTML)
    ↓
JavaScript (assets/js/app.js)
    ↓ fetch()
REST API (api/students.php, api/internships.php)
    ↓
PHP Database Layer (config/database.php)
    ↓
MySQL Database
    (skillsync_students, skillsync_internships tables)
```

---

## API Endpoints Reference

### Get All Students
```bash
GET /api/students.php?action=all
# Returns: Array of student objects
```

### Get All Internships
```bash
GET /api/internships.php?action=all
# Returns: Array of internship objects
```

### Add Student
```bash
POST /api/students.php
# Body: {
#   student_id: "2021-099",
#   name: "Jane Doe",
#   program: "BS IT",
#   year_level: 4,
#   gpa: 1.50,
#   preferred_track: "Web Development",
#   skills: ["PHP", "JavaScript"],
#   completed_subjects: ["Web Systems"]
# }
```

### Update Student
```bash
PUT /api/students.php
# Body: { id: 1, name: "Updated Name", ... }
```

### Delete Student
```bash
DELETE /api/students.php?id=1
```

---

## Data Persistence

✅ **All changes are automatically saved to MySQL:**
- Add a student → Saved to database
- Edit student skills → Saved to database  
- Delete internship → Deleted from database
- Page refresh → Data loads from database (no loss)
- Browser restart → Data still there (persisted in DB)

---

## Troubleshooting

### "Error loading students" message
**Problem:** API endpoint not found
**Solution:** 
- Check that `api/students.php` and `api/internships.php` exist
- Verify folder structure is correct
- Check web server logs

### "Database connection failed"
**Problem:** MySQL server not running or wrong credentials
**Solution:**
- Start MySQL server
- Verify connection details in `config/database.php`
- Test connection: `mysql -u root -p`

### No data showing on page
**Problem:** Database not initialized
**Solution:**
- Run `database/setup.sql`
- Verify database `skillsync` was created
- Check tables in phpMyAdmin

### Changes don't persist after reload
**Problem:** API errors not being caught
**Solution:**
- Open browser Console (F12)
- Look for error messages
- Check Network tab to see API response
- Look at server logs for PHP errors

---

## File Locations

```
skillsync/
├── MYSQL_SETUP.md              ← Detailed setup guide
├── config/
│   └── database.php            ← MySQL connection config
├── api/
│   ├── students.php            ← Student API endpoints
│   └── internships.php         ← Internship API endpoints  
├── database/
│   └── setup.sql               ← Run this to create DB + tables
├── assets/js/
│   └── app.js                  ← Updated to use API
├── includes/
│   ├── header.php
│   └── footer.php
└── index.php                   ← Main entry point
```

---

## Next Steps

### For Development:
- Test all CRUD operations (Create, Read, Update, Delete)
- Monitor Network tab in DevTools to see API calls
- Check database in phpMyAdmin to verify data changes

### For Production:
1. Add input validation (sanitize user inputs)
2. Add security headers
3. Use prepared statements (already using real_escape_string, but prepared statements are better)
4. Add proper error logging
5. Hash admin password
6. Add HTTPS requirement
7. Database backups

### Future Enhancements:
- User authentication system
- Email notifications
- Export to CSV/PDF
- Advanced filtering and search
- Audit logs for admin actions
- Dashboard with statistics

---

## Support

For issues, check:
1. Browser Console (F12) for JavaScript errors
2. Network tab for API response codes
3. MySQL error log
4. Web server error log
