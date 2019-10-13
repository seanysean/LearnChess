const engine = STOCKFISH();
const board = $('#board');
const movesHTML = $('#moves');
const resignBtn = $('#resign');
const flipBoardBtn = $('#flip');
const evalBar = $('#eval-bar');
const evalTextEl = $('#eval-text');
const chess = new Chess('8/PPPP4/8/7k/8/8/2K5/8 w - - 0 1'); // Todo: Change this before prod.
let userColor = '',
    computerColor = '',
    playerTurn = 'user';
const info = {
    yes: 'white',
    no: 'black',
    title: 'Play as'
}
const ev = {
    yes() {
        userColor = 'white';
        computerColor = 'black';
        pickColor.close();
        cg.set({
            turnColor: 'white',
            orientation: 'white',
        });
        if (chess.turn() === 'b') {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 100');
        }
    },
    no() {
        userColor = 'black';
        computerColor = 'white';
        playerTurn = 'computer';
        pickColor.close();
        cg.set({
            turnColor: 'black',
            orientation: 'black',
        });
        if (chess.turn() === 'w') {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 100');
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
                if (userColor === 'black') {
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
                if ((userColor === 'black' && movesTillMate > 0) || (userColor === 'white' && movesTillMate < 0)) {
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
            handleMove(a,b);
            /*const mObj2 = { from: a, to: b, promotion: 'q' };
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
            
            const over = chess.game_over();
            if (over) {
                console.log('hi');
                onGameEnd(userColor);
            } else {
                console.log(over);
                cg.playPremove();
            }*/
        }
    }
}

function onGameEnd(sideToMove) {
    cg.stop();
    const info = {
        yes: 'Ok'
    },
    events = {
        yes() {
            gameOverPopup.close();
        }
    },
    type = 'alert';
    if (chess.in_checkmate()) {
        if (sideToMove === userColor) {
            info.title = 'You lose :(';
        } else {
            info.title = 'You won!';
        }
        info.text = 'Game over by checkmate';
    } else if (chess.in_stalemate()) {
        info.title += 'stalemate';
        info.text = 'Game over by stalemate';
    }
    const gameOverPopup = new Popup(type,info,events);
    gameOverPopup.open();
}

function makeMove(c,c2) {
    return (o,d) => {
        handleMove(o,d);
    }
}

async function handleMove(origin,destination) {
    const mObj = { from: origin, to: destination, promotion: 'q' };
    const m = chess.move(mObj);
    const over = chess.game_over();
    if (m.flags.includes('p')) {
        const promote = await openPromoteOptions(board,m.to,cg,userColor);
        if (promote) {
            chess.undo();
            chess.move({ from: origin, to: destination, promotion: promote });
            moves.innerHTML = chess.pgn();
        }
    }
    if (m.flags.includes('e')) {
        console.log('en passant');
        console.log(chess.turn());
        removePiece = [destination[0]];
        if (chess.turn() === 'w') {
            removePiece[1] = 1 + destination[1];
        } else {
            removePiece[1] = destination[1] - 1;
        }
        const pieces = {}
        pieces[removePiece.join('')] = undefined;
        cg.setPieces(pieces);
    }
    if (playerTurn === 'computer') {
        cg.move(m.from,m.to);
    }
    if (!over) {
        if (playerTurn === 'user') {
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage('go movetime 100');
            console.log('position fen ' + chess.fen());
            playerTurn = 'computer';
        } else {
            cg.set({
                turnColor: getColor(chess.turn()),
                movable: {
                    color: getColor(chess.turn()),
                    dests: toDests(chess)
                }
            });
            playerTurn = 'user';
            cg.playPremove();
        }
    } else {
        if (playerTurn !== 'user') {
            // Since playerTurn is only updated if !over, here I treat playerTurn === computer as meaning that it is the computer moved last.
            onGameEnd(userColor);
        } else {
            onGameEnd(computerColor);
        }
    }
    moves.innerHTML = chess.pgn();
}

resignBtn.addEventListener('click',()=>{
    const info = {
        title: 'Game over',
        text: 'The computer won by resignation',
        yes: 'Ok',
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
