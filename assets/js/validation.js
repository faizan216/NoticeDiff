// Convenience checks only. The server repeats every check.
document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('form[data-validate="register"]');
  if (!form) return;
  var box = document.getElementById('js-errors');

  form.addEventListener('submit', function (event) {
    var problems = [];
    if (form.full_name.value.trim().length < 2) {
      problems.push('Enter your full name.');
    }
    if (form.password.value.length < 8) {
      problems.push('Password must be at least 8 characters.');
    }
    if (form.password.value !== form.confirm_password.value) {
      problems.push('Passwords do not match.');
    }
    if (problems.length > 0) {
      event.preventDefault();
      box.textContent = problems.join(' ');
      box.style.display = 'block';
    }
  });
});
