const username = $('#username'),
      autocomplete = $('#autocomplete-container');
let users = [],
    current = 0,
    last,
    selected,
    next;
username.addEventListener('keyup',e=>{
    if (e.which === 40 || e.which === 38 && users.length > 0) {
        if (last) {
            last.classList.remove('selected');
        }
        if (e.which === 40) {
            if (current < users.length) {
                current++;
                next = users[current - 1];
                next.classList.add('selected');
                last = next;
                selected = true;
                console.log(current);
            } else {
                selected = false;
                current = 6;
            }
        } else {
            if (current > 1) {
                current--;
                next = users[current - 1];
                next.classList.add('selected');
                last = next;
                selected = true;
            } else {
                selected = false;
                current = 0;
            }
        }
    } else {
        autocomplete.innerHTML = '';
        if (username.value.length > 0) {
            autocomplete.style.display = 'block';
            const xhr = new XMLHttpRequest(),
                  url = `/autocomplete?limit=5&username=${username.value}`;
            xhr.responseType = 'json';
            xhr.onreadystatechange = function() {
                if(xhr.readyState === xhr.DONE) {
                    const res = xhr.response;
                    if (!res) {
                        autocomplete.innerHTML = '<p>No user found</p>';
                    } else {
                        users = [];
                        res.forEach(obj=>{
                            const el = document.createElement('div');
                            el.classList = 'user';
                            el.innerHTML = `<i class="fa fa-${obj.icon} state ${obj.state}"></i> ${obj.username}`;
                            el.addEventListener('click',()=>{
                                const usr = el.innerText.replace(' ','');
                                username.value = usr;
                                $('#search').submit();
                            });
                            autocomplete.appendChild(el);
                            users.push(el);
                        });
                    }
                }
            }
            xhr.open('GET',url);
            xhr.send();
        } else {
            autocomplete.style.display = 'none';
        }
    }
});
document.addEventListener('click',()=>{
    if (username !== document.activeElement) {
        autocomplete.style.display = 'none';
    }
});
document.addEventListener('keypress',e=>{
     if (e.which === 13 && selected) {
        e.preventDefault();
        username.value = next.innerText.split(' ').join('');
        selected = false;
    }
});
document.querySelectorAll('.edit').forEach(el=>{
    el.addEventListener('click',()=>{
        let id = el.id.split('edit')[1];
        $('#label').innerHTML = `Edit ${id}`;
        $('#info').value = $('#' + id).innerHTML;
        $('#info').focus();
        $('#info').name = id;
        $('#popup').style.display = 'block';
    });
});
$('#cancel').addEventListener('click',()=>{
    $('#popup').style.display = 'none';
});
