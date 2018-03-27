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
    console.log(scrollTop);
});

// #username

username.addEventListener('keyup',e=>{
    if (e.keyCode === 13) {
        start.click();
    }
    start.href = `/register?username=${username.value}`;
});
