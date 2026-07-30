const form = document.getElementById('loginForm');

//const username = document.getElementById('username');
//const password = document.getElementById('password');

function validateField(field) {
    const errorElement = field.parentElement.querySelector(".error-message");
    if (!field.validity.valid){
        errorElement.textContent = field.validity.customError
            ? field.validationMessage
            : (field.dataset.error || 'This field is not valid.');
        return false;
    }
    else if (field.validity.valid) {
        errorElement.textContent = "";
        return true;
    }
}

form.querySelectorAll('input').forEach(input => {
    input.addEventListener('blur', () => validateField(input));
    // Re-check on every keystroke too, so the error clears as soon as
    // the field becomes valid instead of waiting for the next blur.
    input.addEventListener('input', () => {
        if (input.validity.valid) {
            validateField(input);
        }
    });
});

form.addEventListener('submit', function(event) {
    let isValid = true;
    const fields = form.querySelectorAll('input');

    fields.forEach(function(field) {
        console.log(`Validating field: ${field.name}`);
        const fieldValid = validateField(field);
        if (!fieldValid) {
            isValid = false;
        }
    });

    if (!isValid) {
        // Only block submission when something's actually wrong.
        // A valid submission is left alone so the browser submits it
        // natively — that's what makes it include the name/value of
        // whichever submit button was clicked (e.g. name="login" or
        // name="register"). Calling form.submit() manually, or always
        // calling preventDefault(), strips that out, and PHP never
        // sees $_POST["login"] / $_POST["register"].
        event.preventDefault();
        form.querySelector(":invalid").focus();
    }
});

// --- Live username-availability check (registration page only) ---
// password2 only exists on the registration form, so this only activates there.
const usernameField = document.getElementById('username');
const isRegisterForm = !!document.getElementById('password2');

if (isRegisterForm && usernameField) {
    let debounceTimer;
    const usernameError = usernameField.parentElement.querySelector('.error-message');

    usernameField.addEventListener('input', () => {
        // Clear any stale "taken" state from a previous check while the person keeps typing.
        usernameField.setCustomValidity('');
        clearTimeout(debounceTimer);

        if (!usernameField.value.trim()) return; // let the required-field check handle empty

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`check_username.php?username=${encodeURIComponent(usernameField.value)}`);
                const data = await response.json();

                if (data.exists) {
                    usernameField.setCustomValidity('This username is already taken.');
                    usernameError.textContent = 'This username is already taken.';
                } else {
                    usernameField.setCustomValidity('');
                    if (usernameField.validity.valid) {
                        usernameError.textContent = '';
                    }
                }
            } catch (err) {
                console.error('Username check failed:', err);
            }
        }, 500);
    });
}