const engine = STOCKFISH();
const board = $('#board');
const movesHTML = $('#moves');
const resignBtn = $('#resign');
const flipBoardBtn = $('#flip');
const evalBar = $('#eval-bar');
const evalTextEl = $('#eval-text');
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


// TODO fix en passant

engine.onmessage = function(e) {
    //console.log(e);
    let result = e;
    if (typeof result === 'string') {
        result = result.split('');
        if (result[0] === 'b') {
            result = result.join('').split(' ');
            console.log(result[12]);
            console.log(result)
            let indexOfScoreType = result.indexOf('score') + 1;
            let eval = Number(result[12]);
            let isCentipawn = result[indexOfScoreType] === 'cp';
            if (isCentipawn) {
                if (color === 'black') {
                    eval = -eval;
                }
                let evalText = -(eval / 100).toFixed(2) + '';
                console.log(typeof evalText);
                if (!evalText.match(/\./)) {
                    evalText += '.00';
                } else if (evalText.split('.')[1].length < 2) {
                    evalText += '0';
                }
                evalTextEl.innerHTML = evalText;
                evalPercentage = eval < -1000 ? -1000 : eval > 1000 ? 1000 : eval;
                evalPercentage += 1000;
                evalPercentage = (evalPercentage * 100) / 2000;
            } else {
                let movesTillMate = result[indexOfScoreType + 1];
                evalPercentage = 100;
                if ((color === 'black' && movesTillMate > 0) || (color === 'white' && movesTillMate < 0)) {
                    evalPercentage = 0;
                }
                movesTillMate = movesTillMate < 0 ? -movesTillMate : movesTillMate;
                evalTextEl.innerHTML = `Checkmate in ${movesTillMate}`;
            }
            evalBar.style.width = evalPercentage + '%'; 
            let getBestMove = result[1];
            console.log(getBestMove); // e2e4
            getBestMove = getBestMove.split('');
            let a = getBestMove[0] + getBestMove[1],
                b = getBestMove[2] + getBestMove[3];
            const mObj2 = { from: a, to: b, promotion: 'q' };
            const m2 = chess.move(mObj2);
            //console.log(chess.ascii());
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
