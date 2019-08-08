const switchButton = document.getElementById('dev-theme-switch');
let currentTheme = document.body.classList.contains('dark') ? 'dark' : 'light';
switchButton.addEventListener('change',()=>{
    if (currentTheme === 'dark') {
        document.body.classList.remove('dark');
        currentTheme = 'light';
    } else {
        document.body.classList.add('dark');
        currentTheme = 'dark';
    }
});
document.addEventListener('keypress',e=>{
    if (e.which === 12 && e.shiftKey && e.ctrlKey) {
        // Press Ctrl+Shift+l
        switchButton.click();   
    }
});
