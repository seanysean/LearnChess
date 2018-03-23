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
}, cg = Chessground(document.getElementById('chessground'),config);

splitPGN = pgn.split(/[1-9][.][.][.] |[1-9][.] /).join('').split(' '),
halfMove = 0;

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
            console.log('Good move!');
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
                console.log('Puzzle solved!');
            }
        } else if (!splitPGN[halfMove]) {
            console.log('Puzzle solved!');
        }else {
            console.log('Wrong move!!!');
        }
    }
}
function getColor(c) {
    return c === 'w' ? 'white':'black';
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
