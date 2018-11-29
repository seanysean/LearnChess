document.querySelectorAll('.check-is-valid').forEach(e=>{
    e.classList.remove('check-is-valid'); // Inputs shouldn't start out all red
    e.addEventListener('keydown',()=>{
        if (e.value === '') {
            e.classList.remove('check-is-valid');
        } else {
            e.classList.add('check-is-valid');
        }
    });
});