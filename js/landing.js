const topbar = document.getElementById('topbar'),
      username = document.getElementById('username'),
      start = document.getElementById('start');

// .Topbar

window.addEventListener('scroll',()=>{
    const scrollTop = window.pageYOffset;
    if (scrollTop > 100) {
        topbar.classList = 'topbar small';
    } else {
        topbar.classList = 'topbar';
    }
});

// #username

username.addEventListener('keyup',e=>{
    if (e.keyCode === 13) {
        start.click();
    }
    if (username.value.length > 0) {
        start.href = `/register?username=${username.value}`;
    } else {
        start.href = `/register`;
    }
});

if (document.domain === 'learnchess.tk') {
    setTimeout(()=>{
        const divs = document.getElementsByTagName('DIV');
        divs[divs.length - 1].style.display = 'none';
    }, 1000);
}
