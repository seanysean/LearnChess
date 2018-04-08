const changeContainer = document.getElementById('changes-container');

const changes = [
    {
        date: 'Apr. 8 2018',
        message: '',
        changesList: [
            'You can now <a href="/settings/password">change your password</a>',
            'Updated the close account page',
            'Updated the footer to be more then just a link to this page',
            'Added simple contact page which is really just a link to contact me on lichess',
            '<a href="404" target="_blank">Custom 404 error pages</a>'
        ]
    },
    {
        date: 'Apr. 6 2018',
        message: '<b>NOTE</b>: These changes were not added until April 8.',
        changesList: [
            'Updated the changes page look (this page)',
            'Your contributed puzzles are now on your profile',
            'Some small notifications updates',
            'Other small tweaks'
        ]
    },
    {
        date: 'Apr. 5 2018',
        message: 'Some nice profile additions',
        changesList: [
            'You can now link to your chess.com account',
            'If you have your chess.com username specified in your profile settings, your chess.com rating will show up on your profile',
            'You can also show your lichess rating by entering your lichess username on the settings page',
            'Now you are only shown your read notifications when you click on the bell icon',
            'Notifications page now includes a button to mark all notifications as read'
        ]
    },
    {
        date: 'Apr. 4 2018',
        message: 'Minor updates.',
        changesList: [
            'The changes page added (this one)',
            'Profile page now shows if account is closed'
        ]
    }
];

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
