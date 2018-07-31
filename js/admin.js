const username = document.getElementById('username'),
      autocomplete = document.getElementById('autocomplete-container');
username.addEventListener('keyup',()=>{
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
                    res.forEach(obj=>{
                        const el = document.createElement('div');
                        el.classList = 'user';
                        el.innerHTML = `<i class="fa fa-user state ${obj.state}"></i> ${obj.username}`;
                        el.addEventListener('click',()=>{
                            const usr = el.innerText.replace(' ','');
                            username.value = usr;
                            document.getElementById('search').submit();
                        });
                        autocomplete.appendChild(el);
                    });
                }
            }
        }
        xhr.open('GET',url);
        xhr.send();
    } else {
        autocomplete.style.display = 'none';
    }
});
document.addEventListener('click',()=>{
    if (username !== document.activeElement) {
        autocomplete.style.display = 'none';
    }
});

document.querySelectorAll('.edit').forEach(el=>{
    el.addEventListener('click',()=>{
        let id = el.id.split('edit')[1];
        console.log(id);
        document.getElementById('label').innerHTML = `Edit ${id}`;
        document.getElementById('info').value = document.getElementById(id).innerHTML;
        document.getElementById('info').focus();
        document.getElementById('info').name = id;
        document.getElementById('popup').style.display = 'block';
    });
});
document.getElementById('cancel').addEventListener('click',()=>{
    document.getElementById('popup').style.display = 'none';
});
