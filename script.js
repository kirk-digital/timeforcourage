// ===== Smooth Scroll =====
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", (e) => {
    e.preventDefault();
    document.querySelector(anchor.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});

// ===== Cookie Banner =====
document.addEventListener("DOMContentLoaded", () => {
  const cookieBanner = document.getElementById("cookie-banner");
  const acceptBtn = document.getElementById("accept-cookies");

  if (cookieBanner && acceptBtn) {
    if (!localStorage.getItem("cookiesAccepted")) {
      cookieBanner.style.display = "flex";
    }

    acceptBtn.addEventListener("click", () => {
      localStorage.setItem("cookiesAccepted", "true");
      cookieBanner.style.display = "none";
    });
  }
});

// ===== Phone number validation =====
document.getElementById("contact").addEventListener("submit", (e) => {
  const phone = e.target.phone.value.trim();
  const phonePattern = /^[0-9+\-\s()]{7,}$/;
  if (!phonePattern.test(phone)) {
    e.preventDefault();
    alert("Please enter a valid phone number.");
  }
});
