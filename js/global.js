console.log('LearnChess is open source! View project here: https://github.com/seanysean/LearnChess');

// To remove the one ad.
if (document.domain === 'learnchess.tk') {
    setTimeout(()=>{
        const divs = document.getElementsByTagName('DIV');
        divs[divs.length - 1].style.display = 'none';
    }, 1000);
}

let mClosed = sessionStorage.getItem('closed');
if (!mClosed) {
    mClosed = 'false';
    sessionStorage.setItem('closed','false');
    makeMessage();
} else if (mClosed === 'false') {
    makeMessage();
}
function makeMessage() {
    const message = document.createElement('DIV');
    message.classList = 'site-message';
    message.innerHTML = `<span class="fa fa-info-circle message-type"></span> Thanks for checking this site out! This is the future <a href="https://learnchess.neocities.org">LearnChess</a>.`;
    const close = document.createElement('span');
    close.classList = 'fa fa-close close';
    close.addEventListener('click',()=>{
        sessionStorage.setItem('closed','true');
        message.style.display = 'none';
    });
    message.appendChild(close);
    document.body.appendChild(message);
}

const userInfo = document.createElement('div');
userInfo.classList = 'userinfo-box';
document.body.appendChild(userInfo);
const userInfoLinks = document.querySelectorAll('[userinfo]');
userInfoLinks.forEach(l=>{
    l.addEventListener('mouseover',e=>{
        userInfo.style.display = 'block';
        userInfo.innerHTML = '<div class="loading-container"><div class="loader"></div></div>';
        userInfo.style.left = l.offsetLeft + 'px';
        userInfo.style.top = l.offsetTop - userInfo.offsetHeight + 'px';
        const xhr = new XMLHttpRequest();
              url = `/userinfo?u=${l.getAttribute('userinfo')}`;
        xhr.onreadystatechange = function() {
            if (xhr.readyState === xhr.DONE) {
                const res = xhr.response;
                userInfo.innerHTML = res;
                userInfo.style.top = l.offsetTop -  userInfo.offsetHeight + 'px';
            }
        }
        xhr.open('GET',url);
        xhr.send();
    });
    l.addEventListener('mouseleave',e=>{
        userInfo.style.display = 'none';
    });
});
userInfo.addEventListener('mouseover',()=>{
    userInfo.style.display = 'block';
});
userInfo.addEventListener('mouseleave',()=>{
    userInfo.style.display = 'none';
});
