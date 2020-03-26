/* exported Popup */

const overlay = document.createElement('div');
overlay.classList = 'overlay';
document.body.appendChild(overlay);
class Popup {
    constructor(type,info,ev={}) {
        this.el = document.createElement('div');
        this.el.classList = 'popup';
        this.type = type;
        this.active = false;
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
                if (info.labelText) {
                    const label = document.createElement('label');
                    label.innerHTML = info.labelText;
                    label.style.marginTop = '20px';
                    label.setAttribute('for',info.inputId);
                    this.input.id = info.inputId;
                    inputContainer.appendChild(label);
                    if (info.inputType) {
                        label.style.top = "-30px";
                    }
                }
                if (info.inputType) {
                    this.input.setAttribute('type',info.inputType);
                }
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
                yes.addEventListener('click',this.close.bind(this));
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
            if (type !== 'alert' && info.no) {
                no.classList = 'no-button';
                no.innerHTML = info.no;
                no.addEventListener('click',ev.no);
                this.el.appendChild(no);
            }
            this.el.appendChild(close);
            document.addEventListener('keydown',e=>{
                if (e.key === 'Escape' && this.active) {
                    if (!ev.cls) {
                        if (ev.no) {
                            ev.no();
                        }
                        else {
                            this.close();
                        }
                    } else {
                        ev.cls();
                    }
                } else if (e.key === 'Enter' && this.active) {
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
        if (this.type === 'prompt') {
            this.input.focus();
        }
        this.active = true;
    }
    close(settings={closeOverlay:true}) {
        this.active = false;
        if (settings.closeOverlay === true) {
            overlay.style.opacity = 0;
        }
        this.el.style.transform = 'translate(-50%,-50%) scale(0.8)';
        setTimeout(()=>{
            if (settings.closeOverlay === true) {
                overlay.style.display = 'none';
            }
            this.el.style.display = 'none';
        },300);
    }
    addClass(c) {
        this.el.classList.add(c);
    }
}
