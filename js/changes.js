const changeContainer = document.getElementById('changes-container');
const changes = [
    {
        date: 'Apr. 4 2018',
        message: `Minor updates.`,
        changesList: [
            'The changes page added (this one)',
            'Profile page now shows if account is closed'
        ]
    }
]
changes.forEach(c=>{
    const change = document.createElement('div');
    let linkableDate = c.date.replace(/\s/g,'-').split('.').join('').toLowerCase();
    change.classList = 'change';
    change.innerHTML = `<h2 id="${linkableDate}"><a href="#${linkableDate}" class="change-link"><span class="fa fa-link"></span></a> ${c.date}</h2>`;
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
});
