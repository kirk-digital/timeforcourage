document.addEventListener("DOMContentLoaded", () => {
  // ===== Smooth Scroll =====
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const href = this.getAttribute("href");
      if (href === "#" || href === "") {
        return;
      }
      
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const offsetTop = target.offsetTop - 80; // Account for fixed navbar
        window.scrollTo({
          top: offsetTop,
          behavior: "smooth",
        });
      }
    });
  });

  // ===== Cookie Banner =====
  const cookieBanner = document.getElementById("cookie-banner");
  const acceptBtn = document.getElementById("accept-cookies");

  if (cookieBanner && acceptBtn) {
    // Check if cookies have been accepted
    if (!localStorage.getItem("cookiesAccepted")) {
      cookieBanner.style.display = "flex";
    }

    // Handle accept button click
    acceptBtn.addEventListener("click", () => {
      localStorage.setItem("cookiesAccepted", "true");
      cookieBanner.style.display = "none";
    });
  }

  // ===== Contact Form Handling =====
  const contactForm = document.getElementById("contact-form");
  if (contactForm) {
    // Check for URL parameters to show success/error messages
    const urlParams = new URLSearchParams(window.location.search);
    const sent = urlParams.get("sent");
    const error = urlParams.get("error");

    // Create message container if it doesn't exist
    let messageContainer = document.querySelector(".form-message");
    if (!messageContainer && (sent || error)) {
      messageContainer = document.createElement("div");
      messageContainer.className = "form-message";
      contactForm.insertBefore(messageContainer, contactForm.firstChild);
    }

    if (sent === "1") {
      if (messageContainer) {
        messageContainer.className = "form-message success";
        messageContainer.textContent =
          "Thank you! Your message has been sent successfully. We'll get back to you soon.";
      }
      // Clear URL parameters
      window.history.replaceState({}, document.title, window.location.pathname + "#contact");
    }

    if (error) {
      if (messageContainer) {
        messageContainer.className = "form-message error";
        switch (error) {
          case "missing_fields":
            messageContainer.textContent =
              "Please fill in all required fields.";
            break;
          case "invalid_email":
            messageContainer.textContent = "Please enter a valid email address.";
            break;
          case "send_failed":
            messageContainer.textContent =
              "Sorry, there was an error sending your message. Please try again or contact us directly.";
            break;
          default:
            messageContainer.textContent =
              "Sorry, there was an error. Please try again.";
        }
      }
      // Clear URL parameters
      window.history.replaceState({}, document.title, window.location.pathname + "#contact");
    }

    // Form validation
    contactForm.addEventListener("submit", (e) => {
      const phone = contactForm.querySelector('input[name="phone"]').value.trim();
      const phonePattern = /^[0-9+\-\s()]{7,}$/;
      
      if (!phonePattern.test(phone)) {
        e.preventDefault();
        if (messageContainer) {
          messageContainer.className = "form-message error";
          messageContainer.textContent =
            "Please enter a valid phone number (at least 7 characters).";
        } else {
          alert("Please enter a valid phone number (at least 7 characters).");
        }
        return false;
      }
    });
  }

  // ===== Mobile Menu Toggle =====
  const menuToggle = document.getElementById('menu-toggle');
  const navMenu = document.getElementById('nav-menu');
  const navLinks = document.querySelectorAll('.nav-links a');

  // Toggle menu on button click
  if (menuToggle) {
    menuToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      console.log('Menu toggle clicked'); // debug
      menuToggle.classList.toggle('active');
      navMenu.classList.toggle('active');
    });
  }

  // Close menu when a link is clicked
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      menuToggle.classList.remove('active');
      navMenu.classList.remove('active');
    });
  });

  // Close menu if user clicks outside
  document.addEventListener('click', function(event) {
    if (menuToggle && navMenu) {
      const isMenuToggle = menuToggle.contains(event.target);
      const isNavMenu = navMenu.contains(event.target);
      if (!isMenuToggle && !isNavMenu) {
        menuToggle.classList.remove('active');
        navMenu.classList.remove('active');
      }
    }
  });
});
