function togglePassword() {
    const input = document.getElementById('passwordInput');
    const eye = document.getElementById('eyeSlash');

    if (input.type === 'password') {
        input.type = 'text'
        eye.classList.replace('fa-eye-slash', 'fa-eye')
    } else {
        input.type = 'password'
        eye.classList.replace('fa-eye', 'fa-eye-slash')
    }
}
