/*
 * Lightweight client-side validation.
 * This ONLY improves the user experience (instant feedback).
 * The real, trusted validation always happens again in PHP on the server,
 * because client-side checks can be bypassed.
 */
function showError(input, message) {
  clearError(input);
  const div = document.createElement('div');
  div.className = 'field-error';
  div.textContent = message;
  input.insertAdjacentElement('afterend', div);
  input.style.borderColor = '#b3261e';
}

function clearError(input) {
  input.style.borderColor = '';
  const next = input.nextElementSibling;
  if (next && next.classList.contains('field-error')) {
    next.remove();
  }
}

function validateForm(formId, rules) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener('submit', function (e) {
    let valid = true;

    rules.forEach(function (rule) {
      const input = form.querySelector('[name="' + rule.name + '"]');
      if (!input) return;
      clearError(input);
      const value = input.value.trim();

      if (rule.required && value === '') {
        showError(input, rule.message || 'This field is required.');
        valid = false;
        return;
      }
      if (rule.email && value !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        showError(input, 'Please enter a valid email address.');
        valid = false;
        return;
      }
      if (rule.minLength && value.length > 0 && value.length < rule.minLength) {
        showError(input, 'Must be at least ' + rule.minLength + ' characters.');
        valid = false;
        return;
      }
      if (rule.match) {
        const matchInput = form.querySelector('[name="' + rule.match + '"]');
        if (matchInput && value !== matchInput.value.trim()) {
          showError(input, 'Values do not match.');
          valid = false;
          return;
        }
      }
      if (rule.min !== undefined && value !== '' && Number(value) < rule.min) {
        showError(input, 'Must be at least ' + rule.min + '.');
        valid = false;
        return;
      }
    });

    if (!valid) {
      e.preventDefault();
    }
  });
}
