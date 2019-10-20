const overlay = document.createElement('div');
overlay.classList = 'overlay';
document.body.appendChild(overlay);
//let popupList = [];
class Popup {
    constructor(type,info,ev) {
        this.el = document.createElement('div');
        this.el.classList = 'popup';
        this.type = type;
        this.active = false;
        //popupList.push(this);
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
            close.classList = 'close fa fa-times';
            yes.innerHTML = info.yes;
            if (!ev.yes) {
                yes.addEventListener('click',this.close);
            } else {
                yes.addEventListener('click',ev.yes);
            }
            if (!ev.cls) {
                if (!ev.no) {
                    close.addEventListener('click',()=>{
                        this.close.apply(this);
                    });
                } else {
                    close.addEventListener('click',ev.no);
                }
            } else {
                close.addEventListener('click',ev.cls); 
            }
            this.el.appendChild(yes);
            if (type !== 'alert') {
                no.classList = 'no-button';
                no.innerHTML = info.no;
                no.addEventListener('click',ev.no);
                this.el.appendChild(no);
            }
            this.el.appendChild(close);
            document.addEventListener('keypress',e=>{
                if (e.key === 'Enter' && this.active) {
                    ev.yes();
                }
            });
            document.addEventListener('keydown',e=>{
                // Keypress doesn't seem to detect the escape key on my machine
                if (e.key === 'Escape' && this.active) {
                    switch (!ev.cls) {
                        case true:
                            switch(ev.no) {
                                case true:
                                    ev.no();
                                    break;
                                default:
                                    this.close();
                            }
                            break;
                        default:
                            ev.cls();
                            break;
                    }
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
        if (this.type === 'prompt') {
            this.input.focus();
        }
        this.active = true;
    }
    close() {
        overlay.style.opacity = 0;
        this.el.style.transform = 'translate(-50%,-50%) scale(0.8)';
        setTimeout(()=>{
            overlay.style.display = 'none';
            this.el.style.display = 'none';
        },300);
        this.active = false;
    }
    addClass(c) {
        this.el.classList.add(c);
    }
}
