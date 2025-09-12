# 🎯 Tour System - User Guide

## 🚀 How to Use the Tour System

### For End Users

#### **First Time Login**
- When a new user logs in for the first time, the tour will automatically start
- A welcome modal will appear asking if they want to take the tour or skip it
- The tour consists of 6 steps that highlight key features of the platform

#### **Manual Tour Access**
- Click the "Help Tour" button in the top-right of the dashboard
- This allows users to retake the tour at any time

#### **Tour Steps**
1. **Welcome** - Introduction to the platform
2. **Navigation** - Overview of the main navigation
3. **Dashboard** - Key metrics and dashboard features
4. **Metrics** - Understanding the metric cards
5. **User Menu** - Profile and settings access
6. **Completion** - Tour finished confirmation

---

## 🛠️ For Administrators

### **Checking Tour Status**
```bash
# Check if a user has completed the tour
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->hasCompletedTour(); // Returns true/false
>>> $user->shouldShowTour();   // Returns true/false
```

### **Resetting Tours**
```bash
# Reset tour for a specific user
php artisan tour:reset 1

# Reset tour for all users (with confirmation)
php artisan tour:reset
```

### **API Endpoints**
- `GET /tour/status` - Get user's tour status
- `POST /tour/complete` - Mark tour as completed
- `POST /tour/skip` - Skip the tour
- `POST /tour/step` - Update individual step progress
- `POST /tour/reset` - Reset tour (for testing)

---

## 🔧 For Developers

### **Database Schema**
The tour system adds two columns to the `connect_users` table:
- `tour_completed_at` (timestamp) - When the user completed the tour
- `tour_steps_completed` (jsonb) - Array of completed step IDs

### **Middleware Integration**
The `TourMiddleware` automatically checks if authenticated users should see the tour and passes the `$showTour` variable to all views.

### **Customizing Tour Steps**
Edit the `defineTourSteps()` method in `public/js/tour.js` to:
- Add new steps
- Modify existing step content
- Change element targeting
- Update button labels

### **Element Targeting**
The tour uses CSS selectors to target elements. Multiple selectors can be provided (comma-separated) as fallbacks:

```javascript
attachTo: {
    element: '.primary-element, .fallback-element, .final-fallback',
    on: 'bottom'
}
```

### **Theme Support**
The tour automatically adapts to dark/light themes using CSS custom properties in `public/css/tour.css`.

---

## 🎨 Customization Options

### **Styling**
- Edit `public/css/tour.css` to customize tour appearance
- Tour automatically inherits your application's theme
- Uses CSS custom properties for easy theme modification

### **Content**
- Modify step titles and descriptions in the `defineTourSteps()` method
- Add new steps by extending the steps array
- Customize button text and actions

### **Behavior**
- Adjust auto-start delay in dashboard.blade.php
- Modify tour settings in the Shepherd.js configuration
- Change element targeting strategies

---

## 🐛 Troubleshooting

### **Tour Not Starting**
1. Check browser console for JavaScript errors
2. Verify Shepherd.js is loading (check Network tab)
3. Ensure target elements exist on the page
4. Check if `$showTour` variable is set correctly

### **Missing Target Elements**
- The tour will fallback to center positioning if target elements aren't found
- Check console for warning messages about missing elements
- Update element selectors in `defineTourSteps()`

### **API Errors**
- Verify CSRF token is present in page header
- Check tour routes are properly registered
- Ensure user is authenticated for API calls

### **Theme Issues**
- Check if CSS custom properties are defined
- Verify theme classes are applied correctly
- Test in both light and dark modes

---

## 📊 Analytics & Monitoring

### **Track Tour Completion**
```sql
-- Get tour completion rate
SELECT 
    COUNT(*) as total_users,
    COUNT(tour_completed_at) as completed_tours,
    ROUND(COUNT(tour_completed_at) * 100.0 / COUNT(*), 2) as completion_rate
FROM connect_users;

-- Find users who haven't completed the tour
SELECT id, name, email, created_at 
FROM connect_users 
WHERE tour_completed_at IS NULL 
ORDER BY created_at DESC;
```

### **Monitor Tour Step Progress**
```sql
-- Analyze which steps users complete most
SELECT 
    jsonb_array_elements_text(tour_steps_completed) as step_id,
    COUNT(*) as step_completions
FROM connect_users 
WHERE tour_steps_completed IS NOT NULL
GROUP BY step_id
ORDER BY step_completions DESC;
```

---

## 🚀 Production Deployment

### **Pre-Deployment Checklist**
- [ ] Run database migration: `php artisan migrate`
- [ ] Test tour on staging environment
- [ ] Verify all target elements exist in production layout
- [ ] Check Shepherd.js CDN accessibility
- [ ] Test API endpoints with authentication
- [ ] Verify theme compatibility

### **Performance Considerations**
- Shepherd.js loads from CDN (no local bundle size impact)
- Tour CSS is minimal (~5KB)
- Database queries are optimized with proper indexing
- API calls are throttled to prevent abuse

### **Security Notes**
- All API endpoints require authentication
- CSRF protection is enabled for all tour actions
- Tour data is validated and sanitized
- No sensitive information is exposed in tour content

---

## 📞 Support

If you encounter issues with the tour system:
1. Check this documentation first
2. Review browser console for errors
3. Test with the debug route: `/debug-tour`
4. Contact the development team with specific error details

The tour system is designed to be robust and user-friendly. It gracefully handles missing elements and provides fallbacks to ensure a smooth user experience even if the page layout changes.
