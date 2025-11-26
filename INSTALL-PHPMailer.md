# Installing PHPMailer on Your Server

## The Problem
Your `send-mail.php` file is trying to use PHPMailer, but it's not installed on your server. The error shows:
```
Failed to open stream: No such file or directory in /var/www/timeforcourage.co.uk/send-mail.php
```

## Solution: Install PHPMailer via Composer (Recommended)

### Step 1: Install Composer (if not already installed)
```bash
cd /var/www/timeforcourage.co.uk
curl -sS https://getcomposer.org/installer | php
```

### Step 2: Install PHPMailer
```bash
cd /var/www/timeforcourage.co.uk
php composer.phar require phpmailer/phpmailer
```

This will create a `vendor/` directory with PHPMailer installed.

### Step 3: Verify Installation
```bash
ls -la vendor/phpmailer/phpmailer/
```

You should see the PHPMailer files there.

### Step 4: Test the Form
Try submitting the contact form again. It should now work!

## Alternative: Manual PHPMailer Installation

If you can't use Composer:

1. **Download PHPMailer:**
   ```bash
   cd /var/www/timeforcourage.co.uk
   wget https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
   unzip master.zip
   mv PHPMailer-master PHPMailer
   rm master.zip
   ```

2. **Update send-mail.php** to use the correct path:
   The file will automatically detect PHPMailer in the `PHPMailer/` directory.

## Security Note

⚠️ **IMPORTANT**: Your Gmail password is currently hardcoded in the PHP file. For better security:

1. **Create a config file outside web root:**
   ```bash
   sudo nano /var/www/.timeforcourage-config.php
   ```
   Add:
   ```php
   <?php
   define('SMTP_USERNAME', 'hello.timeforcourage@gmail.com');
   define('SMTP_PASSWORD', 'ebyt marm mozg cxnx');
   ?>
   ```

2. **Update send-mail.php** to include this file:
   ```php
   require_once('/var/www/.timeforcourage-config.php');
   $mail->Username = SMTP_USERNAME;
   $mail->Password = SMTP_PASSWORD;
   ```

3. **Set proper permissions:**
   ```bash
   chmod 600 /var/www/.timeforcourage-config.php
   chown www-data:www-data /var/www/.timeforcourage-config.php
   ```

## Troubleshooting

### If Composer is not available:
Use the manual installation method above, or the file will automatically fall back to PHP's `mail()` function.

### If emails still don't send:
1. Check Gmail App Password is correct
2. Verify SMTP settings
3. Check server firewall allows outbound connections on port 587
4. Check PHP error logs: `sudo tail -f /var/log/php8.3-fpm.log`

### Test SMTP connection:
```bash
telnet smtp.gmail.com 587
```

## Quick Fix: Use Simple mail() Function

If you want to avoid PHPMailer entirely, the updated `send-mail.php` will automatically fall back to PHP's `mail()` function if PHPMailer isn't found. However, this is less reliable than SMTP.

