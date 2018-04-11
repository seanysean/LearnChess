const changeContainer = document.getElementById('changes-cont'),
      change = document.createElement('div'),
      c = changes[0];
let linkableDate = c.date.replace(/\s/g,'-').split('.').join('').toLowerCase();
change.classList = 'change';
change.innerHTML = `<h2>${c.date}</h2>`;
if (c.message) {
    change.innerHTML += `<p class="description">${c.message}</p>`;
}
if (c.changesList) {
    change.innerHTML += '<ul>';
    c.changesList.forEach(ch=>{
        change.innerHTML += `<li>${ch}</li>`;
    });
    change.innerHTML += '</ul>';
}
changeContainer.appendChild(change);
