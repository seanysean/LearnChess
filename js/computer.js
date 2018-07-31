const engine = STOCKFISH();

const movesHTML = document.getElementById('moves');
const chess = new Chess();
const config = {
    coordinates: false,
    turnColor: getColor(chess.turn()),
    orientation: getColor(chess.turn()),
    movable: {
        free: false,
        color: getColor(chess.turn()),
        dests: toDests(chess)
    },
    premovable: {
        enabled: true
    },
    animation: {
        duration: 300
    }
};
const cg = Chessground(document.getElementById('board'),config);
cg.set({
    movable: {
        events: {
            after: makeMove(chess,cg)
        }
    }
});

engine.onmessage = function(e) {
    console.log(e);
    let gBM = e; // getBestMove
    if (typeof gBM === 'string') {
        gBM = gBM.split('');
        if (gBM[0] === 'b') {
            gBM = gBM.join('').split(' ')[1];
            console.log(gBM); // e2e4
            gBM = gBM.split('');
            let a = gBM[0] + gBM[1],
                b = gBM[2] + gBM[3];
            const mObj2 = { from: a, to: b, promotion: 'q' };
            const m2 = chess.move(mObj2);
            console.log(chess.ascii());
            moves.innerHTML = chess.pgn();
            cg.move(m2.from,m2.to);
            if (m2.flags.includes('p')) promote(cg,m2.to,'queen');
            cg.set({
                turnColor: getColor(chess.turn()),
                movable: {
                    color: getColor(chess.turn()),
                    dests: toDests(chess)
                }
            });
            cg.playPremove();
        }
    }
}

function makeMove(c,c2) {
    return (o,d) => {
        const mObj = { from: o, to: d, promotion: 'q' };
        const m = chess.move(mObj);
        if (m.flags.includes('p')) promote(cg,m.to,'queen');
        moves.innerHTML = chess.pgn();
        if (!chess.game_over()) {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 1');
            console.log('position fen ' + chess.fen());
        }
    }
}
