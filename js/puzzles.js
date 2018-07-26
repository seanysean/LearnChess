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
    } else if (c) {
        document.getElementById('copyings').classList.remove('hidden');
        document.getElementById('next').classList.remove('hidden');
        if (s) {
            res.innerHTML = '<i class="fa fa-check"></i> Puzzle solved';
            res.classList = 'correct';
        } else {
            res.innerHTML = '<i class="fa fa-close"></i> Puzzle failed';
            res.classList = 'incorrect';
        }
        let extraStyle = '';
        if (!loggedin || author === infoUsername) {
            extraStyle = ' disabled';
            if (!loggedin) {
                extraStyle += ' data-hint="Log in to like this puzzle"';
            } else {
                extraStyle += ' data-hint="You can\'t give your puzzle a trophy"';
            }
        }
        const el = document.createElement('div');
        el.classList = 'give-a-trophy';
        el.innerHTML = `<button type="submit" id="trophy" class="trophy"${extraStyle}><i class="fa fa-trophy"></i></button> <span id="tCount">${trophies}</span>`;
        const cont = document.createElement('div');
        const el2 = document.createElement('div');
        el2.innerHTML = `<p>Puzzle created by <a href="/member/${author.toLowerCase()}">${author}</a></p>
                        <p>Puzzle rating: ${Math.round(r[0])}</p>`;
        if (!unrated) {
            el2.innerHTML += `<p>Your rating: ${Math.round(r[1])} (${d > 0 ? '+' + d : '–' + (d * -1)})</p>`;
        } else {
            el2.innerHTML += `<p>Unrated</p>`;
        }
        cont.classList = 'credits-div block no-padding';
        el2.classList = 'inner';
        document.querySelector('.right-area').appendChild(cont);
        cont.appendChild(el2); //Don't ask why they're in reverse
        cont.appendChild(el);
        if (loggedin && author !== infoUsername) {
            document.getElementById('trophy').addEventListener('click',updateTrophies);
        }
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
                } else if (resp.ended) {
                    if (resp.correct) {
                        showResponse(true,true,[resp.ratings.puzzle,resp.ratings.user],resp.rating_diff);
                    } else {
                        showResponse(false,true,[resp.ratings.puzzle,resp.ratings.user],resp.rating_diff);
                        setTimeout(()=>{
                            chess.undo();
                            console.log(chess.ascii());
                            cg.set({
                               fen: chess.fen(),
                               turnColor: getColor(chess.turn()),
                               movable: {
                                   color: getColor(chess.turn()),
                                   dests: toDests(chess)
                               }
                            });
                            let rightMove = chess.move(resp.right_move);
                            console.log(rightMove);
                            const shape = [{
                                orig: rightMove.from,
                                dest: rightMove.to,
                                brush: 'green'
                            }];
                            cg.setShapes(shape);
                        }, 500);
                    }
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
