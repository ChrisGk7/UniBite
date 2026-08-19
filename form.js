const signinform = document.getElementById('signinContainer');
const signupform = document.getElementById('signupContainer');
const gotosigninButton = document.getElementById('gotosigninButton');
const gotosignupButton = document.getElementById('gotosignupButton');

if (gotosignupButton && signinform && signupform) {
    gotosignupButton.addEventListener('click', function () {
        signinform.classList.add('hidden');
        signupform.classList.remove('hidden');
    });
}

if (gotosigninButton && signinform && signupform) {
    gotosigninButton.addEventListener('click', function () {
        signupform.classList.add('hidden');
        signinform.classList.remove('hidden');
    });
}
    
// signinButton.addEventListener('click', function (event) {
//     event.preventDefault(); // Prevent the default form submission
//     signinform.style.display = "block";
//     signupform.style.display = "none";
// });

function validateField(field) {
    const errorElement = field.parentElement.querySelector(".error-message");
    if (!errorElement) return true;

    if (!field.validity.valid) {
        
        if(field.validity.customError) {
            errorElement.textContent = field.validationMessage;
        }
        else {
            errorElement.textContent = field.dataset.error || 'This field is not valid.';
        }
        return false;
    } 
        errorElement.textContent = "";
        return true;
    
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
// ............Password match validation....................
const password1Field = document.getElementById('reg_password1');
const password2Field = document.getElementById('reg_password2'); 




  function checkPasswordMatch() {
    if (!password1Field || !password2Field) return;

    // Only validate match if the confirmation field has text in it
    if (password2Field.value.trim() !== '') {
        if (password1Field.value !== password2Field.value) {
            password2Field.setCustomValidity("Passwords do not match");
        } else {
            password2Field.setCustomValidity("");
        }
        validateField(password2Field);
    }
}

// Re-check whenever either password field changes
if (password1Field && password2Field) {
    password1Field.addEventListener('input', checkPasswordMatch);
    password2Field.addEventListener('input', checkPasswordMatch);
}

//...........Username availability check....................
const regUsernameField = document.getElementById('reg_username');

if (regUsernameField) {
    regUsernameField.addEventListener('blur', async function () {
        const username = this.value.trim();
        if (!username) return;

        try {
            const response = await fetch(`check_username.php?username=${encodeURIComponent(username)}`);
            const data = await response.json();

            if (data.exists) {
                // Set the custom validation message on the input
                this.setCustomValidity("Username is already taken.");
            } else {
                this.setCustomValidity("");
            }
            
            // Re-run validateField to draw the text into the <span class="error-message">
            validateField(this);

        } catch (error) {
            console.error("Error checking username availability:", error);
        }
    });
}

//..................Email validity Check...................
const regEmailFieald = document.getElementById('reg_email')
    regEmailFieald.addEventListener('blur',async function () {
        const email = this.value.trim();
        if(!email) return;

        try {

            const response = await fetch(`check_username.php?email=${encodeURIComponent(email)}`);
            const data = await response.json();
        
        
        if(data.exists){

            this.setCustomValidity("User with this email already exists.");
        }
        else{

            this.setCustomValidity("");
        }
        validateField(this);
    }catch(error){
        console.error("Error checking email availability:", error);
    }
    
    });