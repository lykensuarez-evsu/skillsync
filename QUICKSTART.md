# Quick Setup

Don't want to read the full README? Here's the fastest way to get this running.

## 1. Put Files on Your Server

Copy the `skillsync` folder to:
- XAMPP: `C:\xampp\htdocs\skillsync`  
- WAMP: `C:\wamp64\www\skillsync`

## 2. Set Up Database

Go to http://localhost/phpmyadmin and:
1. Click the SQL tab
2. Copy everything from `database/setup.sql`
3. Paste it in and click Go

Done. You now have 3 students and 4 internships loaded.

## 3. Open It

http://localhost/skillsync

## 4. Try It Out

- Pick a student from the dropdown to see their personalized matches
- Click "Admin View" and log in with `admin` / `skillsync123` to add/edit records

## Troubleshooting

**MySQL not connecting?**
- Make sure MySQL is running (XAMPP control panel)
- Check `config/database.php` has the right credentials

**No data showing?**
- Did you run the SQL setup? Check phpMyAdmin to verify the database exists
- Open browser console (F12) to see if there are JavaScript errors

**Can't log into admin?**
- Username: `admin` (lowercase)
- Password: `skillsync123` (exact)

That's seriously it. If you want details on how everything works, read README.md.

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
