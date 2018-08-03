const el = {
    p2: document.getElementById('p2'),
    p3: document.getElementById('p3')
}
const r = {
    valid: 'input-response valid',
    invalid: 'input-response invalid'
}
el.p2.addEventListener('keyup',()=>{
    const res = document.getElementById('passwordResponse');
    const pass = el.p2.value;
    if (pass.length > 4 && pass.length < 21) {
        const removeInvalidChars = pass.replace(/[^a-z1-9_-]/gi,'');
        if (pass === removeInvalidChars) {
            res.innerHTML = ' Password is valid';
            res.classList = r.valid;
        } else {
            res.innerHTML = ' There are invalid characters';
            res.classList = r.invalid;
        }
    } else {
        res.innerHTML = ' Password length is too short or long';
        res.classList = r.invalid;
    }
});
el.p3.addEventListener('keyup',()=>{
    const res = document.getElementById('passwordResponse2');
    if (el.p3.value === el.p2.value) {
        res.innerHTML = ' Passwords match';
        res.classList = r.valid;
    } else {
        res.innerHTML = ' Passwords are not the same';
        res.classList = r.invalid;
    }
});
