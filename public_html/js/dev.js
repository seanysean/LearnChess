const switchButton = document.getElementById('dev-theme-switch');
let currentTheme = document.body.classList.contains('dark') ? 'dark' : 'light';
switchButton.addEventListener('change',()=>{
    console.log('change');
    if (currentTheme === 'dark') {
        document.body.classList.remove('dark');
        currentTheme = 'light';
    } else {
        document.body.classList.add('dark');
        currentTheme = 'dark';
    }
});
document.addEventListener('keypress',e=>{
    if (e.key === 'Ò' && e.shiftKey && e.altKey && e.ctrlKey) {
        // Press Ctrl+Shift+Alt+l
        switchButton.click();   
    }
});
