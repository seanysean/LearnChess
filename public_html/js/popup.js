const overlay = document.createElement('div');
overlay.classList = 'overlay';
document.body.appendChild(overlay);
class Popup {
    constructor(type,info,ev) {
        this.el = document.createElement('div');
        this.el.classList = 'popup';
        if (type === 'confirm') {
            this.el.innerHTML = `<p class="popup-title">${info.title}</p>`;
            if (info.text) {
                this.el.innerHTML += `<p class="popup-text">${info.text}</p>`;
            }
            const yes = document.createElement('button'),
                  no = document.createElement('button'),
                  close = document.createElement('span');
            yes.classList = 'yes-button';
            no.classList = 'no-button';
            close.classList = 'close fa fa-times';
            yes.innerHTML = info.yes;
            no.innerHTML = info.no;
            yes.addEventListener('click',ev.yes);
            no.addEventListener('click',ev.no);
            if (!ev.cls) {
                close.addEventListener('click',ev.no);
            } else {
                close.addEventListener('click',ev.cls); 
            }
            this.el.appendChild(yes);
            this.el.appendChild(no);
            this.el.appendChild(close);
            document.body.addEventListener('keyup',e=>{
                if (e.key === 'Escape') {
                    switch (!ev.cls) {
                        case true:
                            ev.no();
                            break;
                        default:
                            ev.cls();
                            break;
                    }
                } else if (e.key === 'Enter') {
                    ev.yes();
                }
            });
        } else if (type === 'custom') {
            this.el.innerHTML = info.html;
        }
        overlay.appendChild(this.el);
    }
    open() {
        overlay.style.display = 'block';
        setTimeout(()=>{
        overlay.style.opacity = 1;
        },1);
    }
    close() {
        overlay.style.opacity = 0;
        setTimeout(()=>{
        overlay.style.display = 'none';
        },300);
    }
    addClass(c) {
        this.el.classList.add(c);
    }
}
