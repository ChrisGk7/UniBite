const form = document.getElementById('loginForm');
//const username = document.getElementById('username');
//const password = document.getElementById('password');

function validateField(field) {
    const errorElement = field.parentElement.querySelector(".error-message");
    if (!field.validity.valid){
        console.log('Field is invalid', field);
        errorElement.textContent = 'This field cannot be empty.';
        return false;
    }
    console.log('Field is valid',field);
    return true;
}



form.addEventListener('submit', function(event) {
    event.preventDefault();//for preventing submission of form before validation
    
    let  isValid = true;
    const fields = form.querySelectorAll('input');
    
    fields.forEach(function(field) {
    console.log(`Validating field: ${field.name}`);
    const fieldValid = validateField(field);
    if (!fieldValid) {
            isValid = false;
        }
    });
    
    if (isValid) {
        console.log('Form is valid, submitting...');
        
    }
    else {
        console.log('Form is invalid, not submitting.');
    }
});