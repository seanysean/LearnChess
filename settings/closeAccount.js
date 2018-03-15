function closeAccount() {
    const p = document.getElementById('popup');
    p.style.display = 'block';
}
document.getElementById('close-account').addEventListener('click',closeAccount);