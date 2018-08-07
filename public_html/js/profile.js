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
