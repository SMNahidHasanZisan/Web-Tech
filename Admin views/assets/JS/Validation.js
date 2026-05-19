function validEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateRegisterForm() {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var role = document.getElementById('role').value;

    if (name === '' || email === '' || password === '' || confirmPassword === '' || role === '') {
        alert('Please fill all fields.');
        return false;
    }

    if (!validEmail(email)) {
        alert('Enter a valid email.');
        return false;
    }

    if (password.length < 8) {
        alert('Password must be at least 8 characters.');
        return false;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
}

function validateProfileForm() {
    var name = document.getElementById('profile_name').value.trim();
    var email = document.getElementById('profile_email').value.trim();
    var newPassword = document.getElementById('new_password').value;
    var confirmPassword = document.getElementById('profile_confirm_password').value;

    if (name === '' || email === '') {
        alert('Name and email are required.');
        return false;
    }

    if (!validEmail(email)) {
        alert('Enter a valid email.');
        return false;
    }

    if (newPassword !== '') {
        if (newPassword.length < 8 || newPassword !== confirmPassword) {
            alert('New password must be 8 characters and match confirm password.');
            return false;
        }
    }

    return true;
}

function validateContentForm() {
    var title = document.getElementById('content_title').value.trim();
    var description = document.getElementById('content_description').value.trim();
    var category = document.getElementById('content_category').value;
    var file = document.getElementById('content_file').value;

    if (title === '' || description === '' || category === '' || file === '') {
        alert('Please fill all content fields.');
        return false;
    }

    return true;
}

function validateContentEditForm(form) {
    var title = form.title.value.trim();
    var description = form.description.value.trim();
    var category = form.category_id.value;

    if (title === '' || description === '' || category === '') {
        alert('Please fill all edit fields.');
        return false;
    }

    return true;
}

function validateModeratorForm(form, passwordRequired) {
    var name = form.name.value.trim();
    var email = form.email.value.trim();
    var password = form.password.value;

    if (name === '' || email === '') {
        alert('Name and email are required.');
        return false;
    }

    if (!validEmail(email)) {
        alert('Enter a valid email.');
        return false;
    }

    if ((passwordRequired || password !== '') && password.length < 8) {
        alert('Password must be at least 8 characters.');
        return false;
    }

    return true;
}
