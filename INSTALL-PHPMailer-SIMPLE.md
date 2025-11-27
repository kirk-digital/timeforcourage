# Simple PHPMailer Installation (No Composer Required)

## The Problem
Your server's mail system is timing out when trying to connect to Gmail. The logs show:
```
Connection timed out with alt4.gmail-smtp-in.l.google.com
```

This happens because PHP's `mail()` function relies on your server's local mail server, which is having network/firewall issues connecting to Gmail.

## The Solution
Use PHPMailer to send emails directly through Gmail's SMTP servers, bypassing your local mail server.

## Quick Installation (2 minutes)

### Step 1: Download PHPMailer
SSH into your server and run:
```bash
cd /var/www/timeforcourage.co.uk
wget https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
```

### Step 2: Extract and Rename
```bash
unzip master.zip
mv PHPMailer-master PHPMailer
rm master.zip
```

### Step 3: Set Permissions
```bash
chown -R www-data:www-data PHPMailer
chmod -R 755 PHPMailer
```

### Step 4: Test
Try submitting the contact form again. It should work!

## Verify Installation
Check that the files exist:
```bash
ls -la /var/www/timeforcourage.co.uk/PHPMailer/src/
```

You should see:
- Exception.php
- PHPMailer.php
- SMTP.php

## What This Does
- **Bypasses your local mail server** - sends directly through Gmail SMTP
- **More reliable** - Gmail's servers are always available
- **Better deliverability** - emails are less likely to go to spam
- **No Composer needed** - just download and extract

## Troubleshooting

### If wget doesn't work:
```bash
curl -L https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip -o phpmailer.zip
unzip phpmailer.zip
mv PHPMailer-master PHPMailer
rm phpmailer.zip
```

### If you get permission errors:
```bash
sudo chown -R www-data:www-data /var/www/timeforcourage.co.uk/PHPMailer
```

### Check PHP error logs:
```bash
sudo tail -f /var/log/php8.3-fpm.log
```

## Why This Works
- Your server's mail system (sendmail/postfix) is trying to deliver emails directly to Gmail
- This often fails due to firewall rules, network issues, or Gmail blocking direct connections
- PHPMailer uses SMTP authentication to send through Gmail's servers, which is much more reliable


