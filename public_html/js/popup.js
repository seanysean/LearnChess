const overlay = document.createElement('div');
overlay.classList = 'overlay';
document.body.appendChild(overlay);
class Popup {
    constructor(type,info,ev) {
        this.el = document.createElement('div');
        this.el.classList = 'popup';
        if (type !== 'custom') {
            const popupTitle = document.createElement('p');
            popupTitle.classList = 'popup-title';
            popupTitle.innerHTML = info.title;
            this.el.appendChild(popupTitle);
            if (info.text) {
                const popupText = document.createElement('p');
                popupText.classList = 'popup-text';
                popupText.innerHTML = info.text;
                this.el.appendChild(popupText);
            }
            const yes = document.createElement('button'),
                  no = document.createElement('button'),
                  close = document.createElement('span');
            if (type === 'prompt') {
                const inputContainer = document.createElement('div');
                this.input = document.createElement('input');
                inputContainer.classList = 'input-container';
                inputContainer.appendChild(this.input);
                const line = document.createElement('div');
                line.classList = 'line';
                inputContainer.appendChild(line);
                this.el.appendChild(inputContainer);
                this.input.value = info.value;
            }
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
        } else {
            this.el.innerHTML = info.html;
        }
        overlay.appendChild(this.el);
    }
    open() {
        overlay.style.display = 'block';
        this.el.style.display = 'block';
        setTimeout(()=>{
            overlay.style.opacity = 1;
            this.el.style.transform = 'translate(-50%,-50%) scale(1)';
        },1);
        this.input.focus();
    }
    close() {
        overlay.style.opacity = 0;
        this.el.style.transform = 'translate(-50%,-50%) scale(0.8)';
        setTimeout(()=>{
            overlay.style.display = 'none';
            this.el.style.display = 'none';
        },300);
    }
    addClass(c) {
        this.el.classList.add(c);
    }
}
