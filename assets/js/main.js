// Switch between Login and Register tabs
function switchTab(tabName) {
  // Hide all form content
  const forms = document.querySelectorAll(".form-content");
  forms.forEach((form) => form.classList.remove("active"));

  // Remove active class from all buttons
  const buttons = document.querySelectorAll(".tab-btn");
  buttons.forEach((btn) => btn.classList.remove("active"));

  // Show selected form
  const formId = tabName + "Form";
  const form = document.getElementById(formId);
  if (form) {
    form.classList.add("active");
  }

  // Add active class to clicked button
  event.target.classList.add("active");
}

// Open Edit Modal for Match
function openEditModal(matchId) {
  const modal = document.getElementById("editModal");
  if (modal) {
    modal.style.display = "block";
    document.getElementById("match_id").value = matchId;
  }
}

// Close Edit Modal
function closeEditModal() {
  const modal = document.getElementById("editModal");
  if (modal) {
    modal.style.display = "none";
  }
}

// Close modal when clicking outside of it
window.onclick = function (event) {
  const modal = document.getElementById("editModal");
  if (modal && event.target === modal) {
    modal.style.display = "none";
  }
};

// Form Validation
document.addEventListener("DOMContentLoaded", function () {
  // Validate login form
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      const username = document.getElementById("login_username").value.trim();
      const password = document.getElementById("login_password").value;

      if (!username || !password) {
        e.preventDefault();
        alert("Please fill in all fields");
      }
    });
  }

  // Validate register form
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      const username = document.getElementById("reg_username").value.trim();
      const email = document.getElementById("reg_email").value.trim();
      const password = document.getElementById("reg_password").value;
      const confirm = document.getElementById("reg_confirm").value;

      if (!username || !email || !password || !confirm) {
        e.preventDefault();
        alert("Please fill in all fields");
        return;
      }

      if (password !== confirm) {
        e.preventDefault();
        alert("Passwords do not match");
        return;
      }

      if (password.length < 6) {
        e.preventDefault();
        alert("Password must be at least 6 characters");
        return;
      }

      // Simple email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        alert("Please enter a valid email");
        return;
      }
    });
  }

  // Comment form validation
  const commentForms = document.querySelectorAll(".comment-form");
  commentForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const input = form.querySelector('input[name="comment"]');
      if (!input.value.trim()) {
        e.preventDefault();
        alert("Please write a comment");
      }
    });
  });
});
