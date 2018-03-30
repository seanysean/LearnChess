const about = document.getElementById('about');
let aboutText = about.innerHTML,
    edited1 = aboutText.split('<br>').join(' <br> ').split(' ');
edited1.forEach((w,i)=>{
    if (w.startsWith('@')) {
        let edited2 = w.split('');
        edited2.shift();
        edited2 = edited2.join('');
        let replacement = `<a href="/member/${edited2.toLowerCase()}">@${edited2}</a>`;
        replacement = replacement.split(/\n/).join('');
        edited1.splice(i,1,replacement);
        about.innerHTML = edited1.join(' ');
    }
});
