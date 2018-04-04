console.log('LearnChess is open source! View project here: https://github.com/seanysean/LearnChess');

// To remove the one ad.
if (document.domain === 'learnchess.tk') {
    setTimeout(()=>{
        const divs = document.getElementsByTagName('DIV');
        divs[divs.length - 1].style.display = 'none';
    }, 1000);
}

const n = {
    icon: document.getElementById('notification-icon'),
    cont: document.getElementById('notification-container'),
    iCont: document.getElementById('i-container'),
    count: document.getElementById('dCount')
}
if (n.icon) {
    let openNotification = false;
    n.icon.addEventListener('click',()=>{
        if (openNotification) {
            n.cont.style.display = 'none';
            openNotification = false;
        } else {
            n.cont.style.display = 'block';
            openNotification = true
        }
    });
    document.addEventListener('click',e=>{
        if (e.target !== n.icon) {
            n.cont.style.display = 'none';
            openNotification = false;
        }
    });
    function checkNotifications() {
        const xhr = new XMLHttpRequest();
              url = `/notifications?_=${Math.random()}`;
        xhr.responseType = 'json';
        xhr.onreadystatechange = function() {
            if (xhr.readyState === xhr.DONE) {
                const res = xhr.response;
                let unreadCount = 0;
                n.iCont.innerHTML = '';
                if (res[0].message !== '-') {
                    res.forEach(n=>{
                        let el = document.createElement('a');
                        el.href = '#';
                        el.classList.add('notification');
                        if (n.unread === '0') {
                            el.classList.add('read');
                        } else {
                            unreadCount++;
                        }
                        el.innerHTML = `<i class="icon-type fa ${n.icon}"></i> ${n.message}`;
                        console.log(n.iCont);
                        document.getElementById('i-container').appendChild(el);
                    });
                    if (unreadCount > 0) {
                        n.count.setAttribute('data-count',unreadCount);
                    } else {
                        n.count.removeAttribute('data-count');
                    }
                } else {
                    n.iCont.innerHTML = `<p class="nothing-to-see">${res[0].error}</p>`;
                    n.count.removeAttribute('data-count');
                }
            }
        }
        xhr.open('GET',url);
        xhr.send();
    }
    setInterval(checkNotifications, 5000);
    checkNotifications();
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
