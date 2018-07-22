let res = document.getElementById('response');
const chess = new Chess(fen), config = {
    fen: fen,
    coordinates: false,
    turnColor: getColor(chess.turn()),
    orientation: getColor(chess.turn()),
    movable: {
        free: false,
        color: getColor(chess.turn()),
        dests: toDests(chess)
    },
    premovable: {
        enabled: false
    },
    animation: {
        duration: 300
    }
}, cg = Chessground(document.getElementById('chessground'),config);
cg.set({
    movable: {
        events: {
            after: checkMove(chess,cg)
        }
    }
});

let turn = chess.turn() === 'w' ? 'White':'Black',
    gaveTrophy = false,
    fullMove = 0;

function showResponse(s,c,r,d) {
    // s = solved, c = complete, r[0] = puzzle new rating & r[1] = user's new rating, d = user rating diff between new and old rating
    d = Math.round(d);
    console.log(d);
    const unrated = isNaN(d);
    if (s && !c) {
        res.innerHTML = '<i class="fa fa-check"></i> Good move';
        res.classList = 'correct';
    } else if (s && c) {
        document.getElementById('copyings').classList.remove('hidden');
        document.getElementById('next').classList.remove('hidden');
        res.innerHTML = '<i class="fa fa-check"></i> Puzzle solved';
        res.classList = 'correct';
        let extraStyle = '';
        if (!loggedin || author === infoUsername) {
            extraStyle = ' style="pointer-events:none"';
        }
        const el = document.createElement('div');
        el.classList = 'give-a-trophy';
        el.innerHTML = `<button type="submit" id="trophy" class="trophy"${extraStyle}><i class="fa fa-trophy"></i></button> <span id="tCount">${trophies}</span>`;
        const el2 = document.createElement('div');
        el2.innerHTML = `<p>Puzzle created by <a href="/member/${author.toLowerCase()}">${author}</a></p>
                        <p>Puzzle rating: ${Math.round(r[0])}</p>`;
        if (!unrated) {
            el2.innerHTML += `<p>Your rating: ${Math.round(r[1])} (${d > 0 ? '+' + d : '–' + (d * -1)})</p>`;
        } else {
            el2.innerHTML += `<p>Unrated</p>`;
        }
        el2.classList = 'credits-div';
        document.getElementById('res-container').appendChild(el2);
        document.getElementById('res-container').appendChild(el);
        document.getElementById('trophy').addEventListener('click',updateTrophies);
    } else {
        res.innerHTML = '<i class="fa fa-close"></i> Wrong move';
        res.classList = 'incorrect';
    }
}
function checkMove(c,cg) {
    return (o,d) => {
        res.classList.add('loading');
        res.innerHTML = '<div class="loader"></div>';
        fullMove++;
        const mObj = { from: o, to: d,promotion: 'q' };
        const m = chess.move(mObj);
        const xhr = new XMLHttpRequest(),
              url = `../getmoves?move=${m.san.replace('+','%2B').replace('#','%23')}&movenum=${fullMove}&puzzle=${pID}`;
        xhr.responseType = 'json';
        xhr.onreadystatechange = function() {
            if (xhr.readyState === xhr.DONE) {
                const resp = xhr.response;
                if (resp.correct && !resp.ended) {
                    showResponse(true,false);
                    const m2 = chess.move(resp.next);
                    cg.move(m2.from,m2.to);
                    cg.set({
                        turnColor: getColor(chess.turn()),
                        movable: {
                            color: getColor(chess.turn()),
                            dests: toDests(chess)
                        }
                    });
                } else if (resp.correct) {
                    showResponse(true,true,[resp.ratings.puzzle,resp.ratings.user],resp.rating_diff);
                } else {
                    showResponse(false,false);
                    setTimeout(()=>{
                        chess.undo();
                        cg.set({
                            fen: chess.fen(),
                            turnColor: getColor(chess.turn()),
                            movable: {
                                color: getColor(chess.turn()),
                                dests: toDests(chess)
                            }
                        });
                    },500);
                    fullMove--;
                }
            }
        }
        xhr.open('GET',url);
        xhr.send();
    }
}
function updateTrophies() {
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
                t.innerHTML = res.count;
                document.getElementById('trophy').style.pointerEvents = 'none';
            }
        }
        xhr.open('POST',url);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
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

document.getElementById('puzzleURL').value = window.location.href;

res.innerHTML = `<i class="fa fa-info-circle"></i> ${turn} to move`;

const inputs = document.querySelectorAll('input');
inputs.forEach(i=>{
    i.addEventListener('click',()=>{
        i.select();
    });
});
