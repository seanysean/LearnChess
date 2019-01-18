console.log('LearnChess is open source! View project here: https://github.com/seanysean/LearnChess');

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
            openNotification = true;
        }
    });
    document.addEventListener('click',e=>{
        if (e.target !== n.icon && !wasClicked(e.target,'notification')) {
            // Don't hide n.cont if a .notification was clicked
            n.cont.style.display = 'none';
            openNotification = false;
        }
    });
    setInterval(checkNotifications, 10000);
    checkNotifications();
}

function wasClicked(el,t) {
    if (el.classList.contains(t)) {
        return true;
    }
    while (el.parentElement) {
        if (el.parentElement.classList.contains(t)) {
            return true;
        }
        el = el.parentElement;
    }
    return false;
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
                    el.href = n.url;
                    el.classList.add('notification');
                    if (n.unread === '0') {
                        el.classList.add('read');
                    } else {
                        unreadCount++;
                    }
                    el.innerHTML = `<i class="icon-type fa ${n.icon}"></i> ${n.message}`;
                    document.getElementById('i-container').appendChild(el);
                    el.addEventListener('click',e=>{
                        e.preventDefault();
                        markAsRead(n.id,n.url,el);
                    });
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

function markAsRead(id,url,el) {
    const xhr = new XMLHttpRequest(),
          url2 = '/notifications',
          data = `mark=1&n=${id}`
    xhr.responseType = 'json';
    xhr.onreadystatechange = function() {
        if (xhr.readyState === xhr.DONE) {
            if (xhr.response === true) {
                el.classList.add('remove-notification');
                if (url.length) {
                    window.location.href = url;
                } else {
                    setTimeout(()=>{
                       el.style.height = '0';
                       checkNotifications();
                    }, 300);
                }
            }
        }
    }
    xhr.open('POST',url2);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

const powerTip = document.createElement('div');
powerTip.id = 'powerTip';
document.body.appendChild(powerTip);
const powerTipLinks = document.querySelectorAll('[powertip]');
powerTipLinks.forEach(l=>{
    l.addEventListener('mouseover',()=>{
        powerTip.innerHTML = '';
        let obj = JSON.parse(l.getAttribute('powertip'));
        powerTip.style.display = 'block';
        if (obj.type === 'user') {
            powerTip.innerHTML = '<div class="loading-container"><div class="loader"></div></div>';
            let query;
            if (obj.value.startsWith('?')) {
                let edited = obj.value.split('?').join('');
                query = `username=${edited}`;
            } else {
                query = `u=${obj.value}`;
            }
            const xhr = new XMLHttpRequest();
                  url = `/userinfo?${query}`;
            xhr.onreadystatechange = function() {
                if (xhr.readyState === xhr.DONE) {
                    const res = xhr.response;
                    powerTip.innerHTML = res;
                    powerTip.style.top = l.offsetTop -  powerTip.offsetHeight + 'px';
                }
            }
            xhr.open('GET',url);
            xhr.send();
        } else {
            const inner = document.createElement('div');
            powerTip.appendChild(inner);
            loadPosition(inner,obj.value,200);
        }
        powerTip.style.left = l.offsetLeft + 'px';
        powerTip.style.top = l.offsetTop - powerTip.offsetHeight + 'px';
    });
    l.addEventListener('mouseleave',()=>{
        powerTip.style.display = 'none';
        powerTip.style.height = 'auto';
        powerTip.style.width = '150px';
    });
});
powerTip.addEventListener('mouseover',()=>{
    powerTip.style.display = 'block';
});
powerTip.addEventListener('mouseleave',()=>{
    powerTip.style.display = 'none';
});
