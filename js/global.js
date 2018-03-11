// To remove the one ad.
if (document.domain === 'learnchess.000webhostapp.com') {
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
