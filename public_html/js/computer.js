const engine = STOCKFISH();
const board = $('#board');
const movesHTML = $('#moves');
const resignBtn = $('#resign');
const flipBoardBtn = $('#flip');
const chess = new Chess();
let color = '';
const info = {
    yes: 'white',
    no: 'black',
    title: 'Play as'
}
const ev = {
    yes() {
        color = 'white';
        pickColor.close();
        cg.set({
            turnColor: 'white',
            orientation: 'white',
        });
        if (chess.turn() === 'b') {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 1');
        }
    },
    no() {
        color = 'black';
        pickColor.close();
        cg.set({
            turnColor: 'black',
            orientation: 'black',
        });
        if (chess.turn() === 'w') {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 1');
        }
    },
    cls() {
        ev.yes();
    }
}
const config = {
    coordinates: false,
    turnColor: getColor(chess.turn()),
    orientation: getColor(chess.turn()),
    fen: chess.fen(),
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
const cg = Chessground(board,config);
const pickColor = new Popup('confirm',info,ev);
pickColor.open();
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
    return async (o,d) => {
        const mObj = { from: o, to: d, promotion: 'q' };
        const m = chess.move(mObj);
        if (m.flags.includes('p')) {
            const promote = await openPromoteOptions(board,m.to,cg,color);
            if (promote) {
                console.log(promote);
                chess.undo();
                chess.move({ from: o, to: d, promotion: promote });
                moves.innerHTML = chess.pgn();
                if (!chess.game_over()) {
                    engine.postMessage('position fen ' + chess.fen());
                    engine.postMessage('go movetime 1');
                    console.log('position fen ' + chess.fen());
                }
            }
        } else if (!chess.game_over()) {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 1');
            console.log('position fen ' + chess.fen());
        }
        moves.innerHTML = chess.pgn();
    }
}
resignBtn.addEventListener('click',()=>{
    const info = {
        title: 'Game over', // Title of popup
        text: 'The computer won by resignation', // Description text
        yes: 'Ok', // Text for the yes button
    },
    events = {
        yes() {
            resignPopup.close();
        },
        cls() {
            events.yes();
        }
    }
    const resignPopup = new Popup('alert',info,events);
    resignPopup.open();
    cg.stop();
});
flipBoardBtn.addEventListener('click',()=>{
    cg.toggleOrientation();
});
