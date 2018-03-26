const chess = new Chess(fen), config = {
    fen: fen,
    coordinates: false,
    turnColor: getColor(chess.turn()),
    orientation: getColor(chess.turn()),
    movable: {
        free: false,
        color: getColor(chess.turn()),
        dests: toDests(chess),
        events: {
            after: checkMove(chess)
        }
    },
    premovable: {
        enabled: false
    },
    animation: {
        duration: 300
    }
}, cg = Chessground(document.getElementById('chessground'),config), res = document.getElementById('response');

let turn = chess.turn() === 'w' ? 'White':'Black',
    gaveTrophy = false;
res.innerHTML = `<i class="fa fa-info-circle"></i> ${turn} to move`;
splitPGN = pgn.split(/[1-9][.][.][.] |[1-9][.] /).join('').split(' '),
halfMove = 0;

function showResponse(s,d) {
    if (s && !d) {
        res.innerHTML = '<i class="fa fa-check"></i> Good move';
        res.classList = 'correct';
    } else if (s && d) {
        res.innerHTML = '<i class="fa fa-check"></i> Puzzle solved';
        res.classList = 'correct';
        const el = document.createElement('div');
        el.classList = 'give-a-trophy';
        el.innerHTML = `<button type="submit" id="trophy" class="trophy"><i class="fa fa-trophy"></i></button> <span id="tCount">${trophies}</span>`;
        document.getElementById('res-container').appendChild(el);
        document.getElementById('trophy').addEventListener('click',updateTrophies);
    } else {
        res.innerHTML = '<i class="fa fa-close"></i> Wrong move';
        res.classList = 'incorrect';
    }
}
function toDests(c) {
    const dests = {};
    c.SQUARES.forEach(s => {
        const ms = c.moves({square: s, verbose: true});
        if (ms.length) dests[s] = ms.map(m => m.to);
    });
    return dests;
}
function checkMove(c,cg) {
    return (o,d) => {
        const mObj = { from: o, to: d,promotion: 'q' };
        const m = chess.move(mObj);
        console.log(splitPGN[halfMove]);
        if (splitPGN[halfMove] && m.san === splitPGN[halfMove]) {
            showResponse(true,false);
            if (splitPGN[halfMove + 1]) {
                const m2 = chess.move(splitPGN[++halfMove]);
                cg.move(m2.from,m2.to);
                cg.set({
                    turnColor: getColor(chess.turn()),
                    movable: {
                        color: getColor(chess.turn()),
                        dests: toDests(chess)
                    }
                });
                halfMove++;
            } else {
                showResponse(true,true);
            }
        } else if (!splitPGN[halfMove]) {
            showResponse(true,true);
        }else {
            showResponse(false,false);
        }
    }
}
function getColor(c) {
    return c === 'w' ? 'white':'black';
}
function updateTrophies(e) {
    if (!gaveTrophy) {
        const t = document.getElementById('tCount');
        t.innerHTML = '...';
        const xhr = new XMLHttpRequest(),
              url = '/puzzles/star',
              data = `trophy=1&puzzle=${pID}`;
        xhr.responseType = 'json';
        xhr.onreadystatechange = function() {
            if (xhr.readyState === xhr.DONE) {
                const res = xhr.response;
                console.log(res);
                t.innerHTML = Number(trophies) + 1;
                document.getElementById('trophy').style.pointerEvents = 'none';
            }
        }
        xhr.open('POST',url);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        xhr.send(data);
        gaveTrophy = true;
    }
}
cg.set({
    movable: {
        events: {
            after: checkMove(chess,cg)
        }
    }
});

document.getElementById('puzzleURL').innerHTML = window.location.href;
document.getElementById('puzzleFEN').innerHTML = fen;
