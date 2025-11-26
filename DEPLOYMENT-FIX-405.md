# Fixing 405 Not Allowed Error on Nginx

## Problem
When submitting the contact form, you get: **405 Not Allowed, nginx/1.24.0 (Ubuntu)**

This error occurs because nginx is not properly configured to handle POST requests to PHP files.

## Solution

### Option 1: Update Nginx Configuration (Recommended)

1. **Edit your nginx site configuration file:**
   ```bash
   sudo nano /etc/nginx/sites-available/your-site-name
   ```
   (Replace `your-site-name` with your actual site configuration filename)

2. **Find the PHP location block** and ensure it looks like this:
   ```nginx
   location ~ \.php$ {
       try_files $uri =404;
       fastcgi_pass unix:/var/run/php/php-fpm.sock;
       # OR if using TCP: fastcgi_pass 127.0.0.1:9000;
       fastcgi_index index.php;
       fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       include fastcgi_params;
       
       # Ensure POST method is allowed
       fastcgi_param REQUEST_METHOD $request_method;
   }
   ```

3. **Check your PHP-FPM socket location:**
   ```bash
   ls -la /var/run/php/
   ```
   Common locations:
   - `/var/run/php/php8.1-fpm.sock` (for PHP 8.1)
   - `/var/run/php/php8.2-fpm.sock` (for PHP 8.2)
   - `/var/run/php/php-fpm.sock` (generic)

4. **Test nginx configuration:**
   ```bash
   sudo nginx -t
   ```

5. **Reload nginx:**
   ```bash
   sudo systemctl reload nginx
   ```

### Option 2: Check PHP-FPM Status

1. **Check if PHP-FPM is running:**
   ```bash
   sudo systemctl status php8.1-fpm
   # or php8.2-fpm, php-fpm depending on your version
   ```

2. **Restart PHP-FPM if needed:**
   ```bash
   sudo systemctl restart php8.1-fpm
   ```

### Option 3: Verify File Permissions

Ensure `send-mail.php` has correct permissions:
```bash
chmod 644 send-mail.php
chown www-data:www-data send-mail.php
```

### Option 4: Alternative - Use AJAX Form Submission

If nginx configuration changes aren't possible, we can modify the form to use AJAX instead of direct POST. Let me know if you'd like this approach.

## Testing

After making changes, test the contact form again. The form should now submit successfully without the 405 error.

## Common Issues

- **Wrong PHP-FPM socket path**: Check with `ls -la /var/run/php/`
- **PHP-FPM not running**: Restart with `sudo systemctl restart php-fpm`
- **Nginx cache**: Clear with `sudo nginx -s reload`
- **File permissions**: Ensure www-data can read the PHP file


