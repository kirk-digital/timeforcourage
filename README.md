# Time For Courage - Counselling & Life Coaching Website

A modern, responsive website for a UK-based counselling and life coaching business called Time For Courage. Built with HTML, CSS, JavaScript, and PHP for the contact form handler.

## Project Overview

This is a static website project with a simple PHP backend for handling contact form submissions. The design follows a calm, professional aesthetic suitable for a counselling and coaching business, with a warm colour palette and accessible, semantic HTML structure.

## Features

- **Modern, responsive design** - Works seamlessly on desktop, tablet, and mobile devices
- **Accessible markup** - Semantic HTML5, proper labels, ARIA attributes
- **Contact form** - PHP handler with input sanitization and validation
- **Cookie consent banner** - GDPR-compliant cookie consent with localStorage
- **Smooth scrolling** - Enhanced navigation experience
- **SEO optimised** - Meta descriptions on all pages
- **Font Awesome icons** - Social media icons (Instagram, WhatsApp, YouTube)

## Project Structure

```
time_for_courage/
├── index.html              # Homepage
├── about-me.html           # About page
├── counselling.html        # Counselling service page
├── coaching.html           # Life coaching service page
├── workshops.html          # Workshops service page
├── privacy.html            # Privacy & Cookie Policy
├── send-mail.php           # PHP contact form handler
├── style.css               # Main stylesheet
├── script.js               # JavaScript functionality
├── images/                 # Image assets
│   ├── NewLogo.svg         # Main logo
│   └── profile_photo.jpg   # Profile photo
└── README.md               # This file
```

## Design Specifications

### Colours
- Background: `#fffdfb` (warm off-white)
- Text: `#222` (dark grey)
- Primary: `#a87d5a` (warm brown)
- Accent: `#f2ebe3` (soft beige)

### Typography
- Headings: **Playfair Display** (serif)
- Body text: **Poppins** (sans-serif)

### Components
- **Buttons**: Rounded pill shape (`border-radius: 30px`), primary colour background
- **Cards**: White background with subtle shadow, hover lift effect
- **Navbar**: Fixed at top, 80px height, semi-transparent with blur effect
- **Hero**: Full viewport height on homepage, smaller sub-hero on other pages

## Getting Started

### Prerequisites

- PHP 7.0 or higher (for contact form)
- A web server (Apache/Nginx) or PHP's built-in server for local development

### Local Development

1. **Clone or download this repository** to your local machine

2. **Navigate to the project directory**:
   ```bash
   cd time_for_courage
   ```

3. **Start PHP's built-in development server**:
   ```bash
   php -S localhost:8000
   ```

4. **Open your browser** and navigate to:
   ```
   http://localhost:8000
   ```

### Important Notes for Local Development

- **PHP mail() function**: On local development, PHP's `mail()` function may not work unless you have a local mail server (MTA) configured. The contact form will submit, but emails may not be sent.

  **Options for testing locally**:
  - Use **MailHog** or similar local mail testing tool
  - Configure your local environment to send via SMTP
  - Test the form submission flow and check server logs
  - For production deployment, most hosting providers have mail() configured

### Configuration

Before deploying, update the following:

1. **Email Address** (`send-mail.php`):
   - Open `send-mail.php`
   - Replace `YOUR_EMAIL@example.com` with your actual email address (line 16)

2. **Social Media Links** (all HTML pages):
   - Update Instagram, WhatsApp, and YouTube links in the footer
   - Replace placeholders like `YOURUSERNAME`, `YOURNUMBER`, `YOURCHANNEL`

3. **Logo and Images**:
   - Ensure `images/NewLogo.svg` exists and is properly referenced
   - Ensure `images/profile_photo.jpg` exists and is properly referenced

## Deployment

### Production Deployment

1. **Upload all files** to your web server (via FTP, SFTP, or git)

2. **Recommended directory structure**:
   ```
   /var/www/timeforcourage/  (or your web root)
   ```

3. **Set proper file permissions**:
   ```bash
   chown -R www-data:www-data /var/www/timeforcourage
   chmod -R 755 /var/www/timeforcourage
   ```

4. **Configure web server** (if using Apache/Nginx):
   - Ensure PHP is enabled
   - Configure document root to point to the project directory
   - Set up SSL certificate (Let's Encrypt recommended)

5. **Test the contact form**:
   - Submit a test message
   - Check that emails are received
   - Verify error handling works correctly

### SSL/HTTPS Setup (Recommended)

For production, set up SSL/HTTPS using Let's Encrypt:

```bash
# Example for Apache with Let's Encrypt
sudo certbot --apache -d yourdomain.com
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Accessibility

This website follows WCAG 2.1 Level AA guidelines:
- Semantic HTML5 elements
- Proper heading hierarchy
- Alt text on all images
- Form labels with `for` attributes
- ARIA labels on social links
- Keyboard navigation support
- Sufficient colour contrast

## Features in Detail

### Contact Form

The contact form includes:
- Required fields: Name, Email, Phone, Message
- HTML5 validation
- PHP sanitization and validation
- Email notification on submission
- Success/error message handling
- Client-side and server-side validation

### Cookie Consent

- Cookie consent banner appears on first visit
- User preference stored in localStorage
- GDPR-compliant implementation
- Link to privacy policy

### Smooth Scrolling

- All anchor links (#) use smooth scroll
- Accounts for fixed navbar height
- Works across all pages

## Troubleshooting

### Contact form not sending emails

1. **Check PHP mail() configuration**:
   - Verify `sendmail_path` in `php.ini`
   - Check server logs for errors
   - Test mail() function with a simple script

2. **Verify email address**:
   - Ensure correct email in `send-mail.php`
   - Check spam/junk folders

3. **For production hosting**:
   - Many shared hosts require SMTP configuration
   - Consider using PHPMailer or similar library for SMTP support

### Images not displaying

- Check file paths (case-sensitive on Linux)
- Verify image files exist in `images/` directory
- Check file permissions (644 for files, 755 for directories)

### Styling issues

- Clear browser cache
- Verify `style.css` is linked correctly
- Check browser console for CSS errors
- Ensure Google Fonts are loading

## Customisation

### Adding New Pages

1. Copy an existing page (e.g., `counselling.html`)
2. Update content and meta tags
3. Add navigation link in header
4. Maintain consistent styling and structure

### Changing Colours

Edit CSS variables in `style.css`:
```css
:root {
  --bg: #fffdfb;
  --text: #222;
  --primary: #a87d5a;
  --accent: #f2ebe3;
}
```

### Modifying Fonts

Update Google Fonts link in HTML `<head>` and CSS font-family declarations.

## Support

For issues or questions:
- Check this README first
- Review code comments in `send-mail.php` and `script.js`
- Test in different browsers and devices

## License

This project is proprietary and confidential. All rights reserved by Time For Courage.

## Credits

- **Fonts**: Google Fonts (Playfair Display, Poppins)
- **Icons**: Font Awesome 6.5.0
- **Design**: Custom design for Time For Courage

---

**Last Updated**: January 2025
