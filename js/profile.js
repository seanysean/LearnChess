if (document.getElementById('about')) {
    const about = document.getElementById('about');
    let aboutText = about.innerHTML,
        edited1 = aboutText.split('<br>').join(' <br> ').split(' ');
    edited1.forEach((w,i)=>{
        if (w.startsWith('@')) {
            let edited2 = w.split('');
            edited2.shift();
            edited2 = edited2.join('');
            let replacement = `<a href="/member/${edited2.toLowerCase()}" userinfo="?${edited2.toLowerCase()}">@${edited2}</a>`;
            replacement = replacement.split(/\n/).join('');
            edited1.splice(i,1,replacement);
            about.innerHTML = edited1.join(' ');
        }
        if (w.startsWith('p#')) {
            let edited2 = w.split(/p/);
            edited2.shift();
            edited2 = edited2.join('');
            let replacement = `<a href="/puzzles/view/${edited2.split('#').join('')}">p${edited2}</a>`;
            replacement = replacement.split(/\n/).join('');
            edited1.splice(i,1,replacement);
            about.innerHTML = edited1.join(' ');
        }
    });
}

if (web.chessCom) {
    const cont = document.getElementById('chessComInfo'),
          xhr = new XMLHttpRequest(),
          url = `https://api.chess.com/pub/player/${web.chessCom}/stats`;
    xhr.responseType = 'json';
    xhr.onreadystatechange = function() {
        if (xhr.readyState === xhr.DONE) {
            const res = xhr.response;
            let rating;
            if (res.chess_rapid) {
                rating = res.chess_rapid.last.rating;
            } else if (res.chess_daily) {
                rating = res.chess_daily.last.rating;
            } else if (res.chess_blitz) {
                rating = res.chess_blitz.last.rating;
            } else if (res.chess_bullet) {
                rating = res.chess_bullet.last.rating;
            } else {
                rating = 1200;
            }
            cont.innerHTML = `<span class="info-title">Chess.com</span> ${rating}`;
        }
    }
    xhr.open('GET',url);
    xhr.send();
}
if (web.lichess) {
    const cont = document.getElementById('lichessInfo'),
          xhr = new XMLHttpRequest(),
          url = `https://lichess.org/api/user/${web.lichess}`;
    xhr.responseType = 'json';
    xhr.onreadystatechange = function() {
        if (xhr.readyState === xhr.DONE) {
            const res = xhr.response;
            let rating;
            if (!res.perfs.classical.prov) {
                rating = res.perfs.classical.rating;
            } else if (!res.perfs.rapid.prov) {
                rating = res.perfs.rapid.rating;
            } else if (!res.perfs.correspondence.prov) {
                rating = res.perfs.correspondence.rating;
            } else if (!res.perfs.blitz.prov) {
                rating = res.perfs.blitz.rating;
            } else {
                rating = 1500;
            }
            cont.innerHTML = `<span class="info-title">Lichess.org</span> ${rating}`;
        }
    }
    xhr.open('GET',url);
    xhr.send();
}
