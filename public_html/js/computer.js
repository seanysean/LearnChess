const engine = STOCKFISH();
const board = $('#board');
const movesHTML = $('#moves');
const resignBtn = $('#resign');
const flipBoardBtn = $('#flip');
const evalBar = $('#eval-bar');
const evalTextEl = $('#eval-text');
const evalHelpBtn = $('#eval-help');
const takeBackBtn = $('#takeback');
const hintBtn = $("#hint");
const chess = new Chess();
let userColor = '',
    computerColor = '',
    playerTurn,
    hint,
    computerTimePerMove = 100, // In ms
    areMovesMade = false;
const config = {
    coordinates: false,
    orientation: getColor(chess.turn()),
    fen: chess.fen(),
    premovable: {
        enabled: true
    },
    animation: {
        duration: 300
    }
};
const pickSideSettings = [
    {
        yes: '<img src="../images/pieces/merida/wK.svg" alt="white" style="width:70px; height: 70px" />',
        no: '<img src="../images/pieces/merida/bK.svg" alt="black" style="width:70px; height: 70px" />',
        title: 'Play as'
    },
    {   
        yes() {
            userColor = 'white';
            computerColor = 'black';
            pickSidePopup.close({closeOverlay:false});
            cg.set({
                turnColor: 'white',
                orientation: 'white',
            });
            setTimeout(chooseLevelPopup.open,300); // Delay is needed because without it the choose level popup will instantly close if you press esc
        },
        no() {
            userColor = 'black';
            computerColor = 'white';
            pickSidePopup.close({closeOverlay:false});
            cg.set({
                turnColor: 'black',
                orientation: 'black',
            });
            setTimeout(chooseLevelPopup.open,300);
        },
        cls() {
            pickSideSettings[1].yes();
        }
    },
];
const chooseLevelSettings = [
    {
        title: 'Difficulty',
        text: 'How much time (in milliseconds) should the computer think per move? Note that high values may cause your browser to run slower.',
        inputId: 'itCanBeRandom',
        inputType: 'number',
        labelText: 'Time/move in ms',
        value: 100,
        yes: 'Next'
    },
    {
        yes() {
            computerTimePerMove = parseInt(chooseLevelPopup.input.value);
            chooseLevelPopup.close({closeOverlay:false});
            setTimeout(chooseFENPopup.open,300);
        },
        cls() {
            chooseLevelSettings[1].yes();
        }
    }
];
const chooseFENSettings = [
    {
        title: 'Input custom FEN',
        inputId: 'fenInput',
        disableInputSpellCheck: true,
        labelText: 'FEN',
        value: 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR',
        yes: 'Start Game'
    },
    {
        yes() {
            let inputtedFen = chooseFENPopup.input.value;
            if (!/\s/.test(inputtedFen)) {
                inputtedFen += ' w KQkq - 0 1';
            }
            let validation = chess.validate_fen(inputtedFen);
            if (validation.valid) {
                chess.load(inputtedFen);
                if (chess.game_over()) {
                    chooseFENPopup.showMessage('error',`FEN is of a game that has ended`);
                } else {
                    chooseFENPopup.close();
                    startGame();
                }
            } else {
                chooseFENPopup.showMessage('error',validation.error);
            }
        },
        cls() {
            chooseFENSettings[1].yes();
        }
    }
]

const cg = Chessground(board,config);
const pickSidePopup = new Popup('confirm',pickSideSettings[0],pickSideSettings[1]);
const chooseLevelPopup = new Popup('prompt',chooseLevelSettings[0],chooseLevelSettings[1]);
const chooseFENPopup = new Popup('prompt',chooseFENSettings[0],chooseFENSettings[1]);
pickSidePopup.open();
pickSidePopup.addClass('pick-color');
cg.set({
    movable: {
        events: {
            after: makeMove(chess,cg)
        }
    }
});

const evalHelpPopupConfig = {
    title: 'What is the evaluation?',
    text: `The evaluation is the how large one side's advantage is in pawns. Negative numbers are good for black, while positive numbers are good for white. So, an evaluation of -5.32 would mean that black has an advantage of 5 and a bit pawns.`,
    yes: 'Done'
}

const evalHelpPopup = new Popup('alert',evalHelpPopupConfig);

engine.onmessage = function(e) {
    let result = e;
    if (typeof result === 'string') {
        result = result.split('');
        if (result[0] === 'b') {
            result = result.join('').split(' ');
            if (!hint) {
                hintBtn.disabled = false;
            }
            hint = result[5].slice(0,2);
            let indexOfScoreType = result.indexOf('score') + 1;
            let eval = Number(result[12]);
            let isCentipawn = result[indexOfScoreType] === 'cp';
            if (isCentipawn) {
                if (userColor === 'black') {
                    eval = -eval;
                }
                let evalText = -(eval / 100).toFixed(2) + '';
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
        }
    }
}

function startGame() {
    cg.set({
        fen: chess.fen(),
        movable: {
            free: false,
            color: getColor(chess.turn()),
            dests: toDests(chess),
            turnColor: getColor(chess.turn()),
        },    
    });
    if ((chess.turn() === 'b' && userColor === 'white') || (chess.turn() === 'w' && userColor === 'black')) {
        playerTurn = 'computer';
        engine.postMessage('position fen ' + chess.fen());
        engine.postMessage(`go movetime ${computerTimePerMove}`);
    } else {
        playerTurn = 'user';
    }
}

function onGameEnd(sideToMove,overByResignation) {
    resignBtn.disabled = true;
    takeBackBtn.disabled = true;
    hintBtn.disabled = true;
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
    if (overByResignation) {
        info.title = 'You lose :(';
        info.text = 'Game over by resignation';
    } else if (chess.in_checkmate()) {
        if (sideToMove === userColor) {
            info.title = 'You lose :(';
        } else {
            info.title = 'You won!';
        }
        info.text = 'Game over by checkmate';
    } else if (chess.in_stalemate()) {
        info.title = 'Draw';
        info.text = 'Game over by stalemate';
    } else if (chess.in_threefold_repetition()) {
        info.title = 'Draw';
        info.text = 'Game over by threefold repitition';
    } else if (chess.in_draw()) {
        info.title = 'Draw';
        info.text = 'Game is drawn';
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
    if (playerTurn === 'computer') {
        cg.move(m.from,m.to);
    }
    if (m.flags.includes('p')) {
        if (playerTurn === 'user') {
            const promote = await openPromoteOptions(board,m.to,cg,userColor);
            if (promote) {
                chess.undo();
                chess.move({ from: origin, to: destination, promotion: promote });
                updateMovesList();
            }
        } else {
            promote(cg,m.to,'queen');
        }
    }
    if (m.flags.includes('e')) {
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
    if (!over) {
        if (playerTurn === 'user') {
            takeBackBtn.disabled = true;
            engine.postMessage('position fen ' + chess.fen());
            engine.postMessage(`go movetime ${computerTimePerMove}`);
            playerTurn = 'computer';
        } else {
            if (userColor === 'black' && chess.history().length === 1) {
                takeBackBtn.disabled = true;
            } else{
                takeBackBtn.disabled = false;
            }
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
            // Since playerTurn is only updated if !over, here playerTurn === computer means that the computer moved last.
            onGameEnd(userColor);
        } else {
            onGameEnd(computerColor);
        }
    }
    updateMovesList();
}

function updateMovesList() {
    if (areMovesMade === false) {
        areMovesMade = true;
        $("#moves-block").style.display = 'block';
    }
    movesHTML.innerHTML = removeHeaders(chess.pgn());
}

function showHint() {
    if (areMovesMade) {
        const shape = [{
            orig: hint,
            brush: 'green'
        }];
        cg.setShapes(shape);
    }
}
takeBackBtn.addEventListener('click',()=>{
    chess.undo();
    chess.undo();
    cg.set({
        fen: chess.fen(),
        turnColor: getColor(chess.turn()),
        movable: {
          color: getColor(chess.turn()),
          dests: toDests(chess)
        }
    });
    updateMovesList();
    if (userColor === 'black' && chess.history().length === 1) {
        takeBackBtn.disabled = true;
    }
});

resignBtn.addEventListener('click',()=>{
    onGameEnd(undefined,true);
});
flipBoardBtn.addEventListener('click',()=>{
    cg.toggleOrientation();
});
evalHelpBtn.addEventListener('click',()=>{
    evalHelpPopup.open();
});
hintBtn.addEventListener('click',showHint);
document.body.addEventListener('keydown',e=>{
    if (e.key.toLowerCase() === 'h') {
        showHint();
    } else if (e.key.toLowerCase() === 'f') {
        cg.toggleOrientation();
    }
});
