function valid(text,min,max,inputType,msgContainer) {
    const el = $('#' + msgContainer);
    let inputEdited = inputType.split('');
    if (text.toLowerCase() === 'index' && inputType === 'username') {
        el.innerText = ` ${text} is invalid.`;
        el.classList = 'input-response invalid';
        return;
    }
    inputEdited[0] = inputEdited[0].toUpperCase();
    inputEdited = inputEdited.join('');
    if ((text.length > min) && (text.length < max)) {
        const removeInvalidChars = text.replace(/[^a-z1-9_-]/gi,'');
        if (text === removeInvalidChars) {
            if (inputType === 'username') {
                const xhr = new XMLHttpRequest();
                const url = `/autocomplete?exists=1&username=${text}`;
                xhr.responseType = 'json';
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.response.exists === true) {
                            el.innerText = ' Username already exists';
                            el.classList = 'input-response invalid';
                        } else {
                            el.innerText = ` Valid ${inputType}`;
                            el.classList = 'input-response valid';
                        }
                    }
                }
                xhr.open('GET',url);
                xhr.send();
            } else {
                el.innerText = ` Valid ${inputType}`;
                el.classList = 'input-response valid';
            }
        } else {
            el.innerText = ` ${inputEdited} has invalid characters.`;
            el.classList = 'input-response invalid';
        }
    } else {
        el.innerText = ` ${inputEdited} too long or too short.`;
        el.classList = 'input-response invalid';
    }
}
function login(username,password) {
    let form = document.createElement('form');
    form.action = '/login';
    form.method = 'POST';
    form.style.display = 'none';
    form.innerHTML = `<input name="username" value='${username}'><input name="password" value='${password}'>`;
    document.body.appendChild(form);
    form.submit();
}
const inputs = {
    username: $('#username'),
    password: $('#password')
}
if (inputs.username && inputs.password) {
inputs.username.addEventListener('keyup',()=>{
    valid(inputs.username.value,3,18,'username','usernameResponse');
});
inputs.password.addEventListener('keyup',()=>{
    valid(inputs.password.value,5,21,'password','passwordResponse');
});
}
