const signinContainer = document.getElementById('signinContainer');
const signupContainer = document.getElementById('signupContainer');
const signinButton = document.getElementById('signinButton');
const signupButton = document.getElementById('signupButton');

signupButton.addEventListener('click', function () {
    signinContainer.style.display = "none";
    signupContainer.style.display = "block";
});

signinButton.addEventListener('click', function (event) {
    // signinButton is a submit button on the sign-in form, so only
    // toggle containers here if it's being clicked from the sign-up
    // side (it isn't currently, but this guards against future markup
    // changes). Normal sign-in submits proceed as usual.
});

function validateField(field) {
    const errorElement = field.parentElement.querySelector(".error-message");
    if (!errorElement) return true;

    if (!field.validity.valid) {
        errorElement.textContent = field.dataset.error || 'This field is not valid.';
        return false;
    } else {
        errorElement.textContent = "";
        return true;
    }
}


document.querySelectorAll('form').forEach(function (form) {
    form.querySelectorAll('input').forEach(function (input) {
        input.addEventListener('blur', () => validateField(input));
        // Re-check on every keystroke too, so the error clears as soon
        // as the field becomes valid instead of waiting for the next blur.
        input.addEventListener('input', () => {
            if (input.validity.valid) {
                validateField(input);
            }
        });
    });

    form.addEventListener('submit', function (event) {
        let isValid = true;
        const fields = form.querySelectorAll('input');

        fields.forEach(function (field) {
            const fieldValid = validateField(field);
            if (!fieldValid) {
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault(); // only block submission when something's invalid
            const firstInvalid = form.querySelector(":invalid");
            if (firstInvalid) firstInvalid.focus();
        }
        // If isValid, let the form submit normally to register_user_student.php for
        // server-side validation/processing.
    });
});