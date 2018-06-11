console.log('LearnChess is open source! View project here: https://github.com/seanysean/LearnChess');

// To remove the one ad.
if (document.domain === 'learnchess.tk') {
    setTimeout(()=>{
        const divs = document.getElementsByTagName('DIV');
        divs[divs.length - 1].style.display = 'none';
    }, 1000);
}

const pageJson = document.getElementById('js_info');
      info = JSON.parse(pageJson.value),
      loggedin = info.loggedin,
      infoUsername = info.username;
pageJson.remove();

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
    setInterval(checkNotifications, 5000);
    checkNotifications();
}

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
                    document.getElementById('i-container').appendChild(el);
                });
                if (unreadCount > 0) {
                    n.count.setAttribute('data-count',unreadCount > 9 ? '9+' : unreadCount);
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

function addCountdown() {
    let currentTime = new Date(),
        sleepTime = new Date().setUTCHours(3),
        getCurrentTime = currentTime.getTime(),
        time,
        hoursLeft;
    if (currentTime.getUTCHours() > 3) {
        sleepTime += 86400000;
        console.log(currentTime.getUTCHours());
    }
    time = sleepTime - getCurrentTime;
    hoursLeft = (time / 1000 / 60 / 60) + 3; // The +3 is because the time was set locally (GMT -3)
    // console.log(time + ' ' + getCurrentTime + ' ' + sleepTime);
    const countdown = document.createElement('DIV');
    countdown.classList = 'countdown';
    countdown.innerHTML = `<span class="timer-icon" data-count="${hoursLeft}"><i class="fa fa-moon-o"></i></span><span class="timeleft">Website goes offline in ${hoursLeft} hour${hoursLeft !== 1 ? 's':''}</span>`;
    document.body.appendChild(countdown);
}

const userInfo = document.createElement('div');
userInfo.classList = 'userinfo-box';
document.body.appendChild(userInfo);
const userInfoLinks = document.querySelectorAll('[userinfo]');
userInfoLinks.forEach(l=>{
    l.addEventListener('mouseover',()=>{
        userInfo.style.display = 'block';
        userInfo.innerHTML = '<div class="loading-container"><div class="loader"></div></div>';
        userInfo.style.left = l.offsetLeft + 'px';
        userInfo.style.top = l.offsetTop - userInfo.offsetHeight + 'px';
        let query;
        if (l.getAttribute('userinfo').startsWith('?')) {
            let edited = l.getAttribute('userinfo').split('?').join('');
            query = `username=${edited}`;
        } else {
            query = `u=${l.getAttribute('userinfo')}`;
        }
        const xhr = new XMLHttpRequest();
              url = `/userinfo?${query}`;
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
    l.addEventListener('mouseleave',()=>{
        userInfo.style.display = 'none';
    });
});
userInfo.addEventListener('mouseover',()=>{
    userInfo.style.display = 'block';
});
userInfo.addEventListener('mouseleave',()=>{
    userInfo.style.display = 'none';
});

addCountdown();
