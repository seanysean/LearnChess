let turn = 'white',
    isNextStep = false;

const config = {
    coordinates: false,
    draggable: {
        deleteOnDropOff: true
    },
    events: {
        change: updateFEN,
        select: setPiece
    }
}
const cg = Chessground(document.getElementById('cg'),config);
let selection = false;

function setPiece(s) {
    if (selection) {
        const pieces = cg.state.pieces;
        const selectedPiece = document.querySelector('.selectedPiece').getAttribute('data-piece').split(' ');
        pieces[s] = {
            color: selectedPiece[0],
            role: selectedPiece[1]
        }
        cg.setPieces(pieces);
        updateFEN();
    }
}

const tools = {
    container: document.getElementById('t-cont'),
    flip: document.getElementById('flip'),
    analyze: document.getElementById('analyze'),
    initial: document.getElementById('initial'),
    color: document.getElementById('color'),
    empty: document.getElementById('empty'),
    clearSelection: document.getElementById('clrSelect'),
    undo: document.getElementById('undo')
}
tools.flip.addEventListener('click',()=>{
    cg.toggleOrientation();
});
tools.initial.addEventListener('click',()=>{
    cg.set({
        fen: 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR'
    });
    updateFEN();
});
tools.color.addEventListener('click',()=>{
    if (turn === 'white') { 
        turn = 'black';
        tools.color.children[0].children[0].classList = 'fa fa-circle';
        tools.color.setAttribute('data-hint','Black to play');
    } else {
        turn = 'white';
        tools.color.children[0].children[0].classList = 'fa fa-circle-o';
        tools.color.setAttribute('data-hint','White to play');
    }
});
tools.empty.addEventListener('click',()=>{
    cg.set({
        fen: '8/8/8/8/8/8/8/8'
    });
    updateFEN();
});
tools.clearSelection.addEventListener('click',()=>{
    document.getElementsByClassName('selectedPiece')[0].classList.remove('selectedPiece');
    selection = false;
});

function updateFEN() {
    if (isNextStep) {
        return;
    }
    document.getElementById('fen').value = cg.getFen() + (turn === 'white' ? ' w' : ' b') + ' - - 0 1';
    tools.analyze.href = 'https://lichess.org/analysis/' + cg.getFen();
}

const dataPieces = document.querySelectorAll('[data-piece]'),
      fenInput = document.getElementById('fen');
dataPieces.forEach(p=>{
    p.addEventListener('click',()=>{
        let selectedPiece = document.getElementsByClassName('selectedPiece')[0];
        if (selectedPiece) {
            selectedPiece.classList.remove('selectedPiece');
        }
        p.classList.add('selectedPiece');
        selection = true;
    });
});
fenInput.addEventListener('keyup',()=>{
    cg.set({
        fen: fenInput.value
    });
});

// Next step...

const nextStep = document.getElementById('next');
nextStep.addEventListener('click',()=>{
    if (selection) {
        document.getElementsByClassName('selectedPiece')[0].classList.remove('selectedPiece');
        selection = false;
    }
    isNextStep = true;
    document.getElementById('fen-cont').style.display = 'none';
    let t = ['initial','color','empty','clearSelection'];
    t.forEach(i=>{
        if (i !== 'color') {
        tools[i].parentElement.style.display = 'none';
        } else {
            tools[i].style.display = 'none';
        }
    });
    tools.undo.parentElement.style.display = 'block';
    nextStep.style.display = 'none';
    document.getElementById('pgn-cont').style.display = 'block';
    document.getElementById('explain-cont').style.display = 'block';
    document.getElementById('submit').style.display = 'inline-block';
    document.getElementById('cancel').style.display = 'inline-block';
    const spares = document.querySelectorAll('.spare');
    spares.forEach(el=>{
        el.classList.add('disabled');
    });
    const chess = new Chess(cg.getFen() + (turn === 'white' ? ' w' : ' b') + ' - - 0 1');
    document.getElementById('fen').value = chess.fen();
    cg.set({
        turnColor: getColor(chess.turn()),
        movable: {
            free: false,
            dests: toDests(chess),
            events: {
                after: changeTurn(chess,cg)
            }
        },
        draggable: {
            deleteOnDropOff: false
        }
    });
    function toDests(c) {
        const dests = {};
        c.SQUARES.forEach(s => {
            const ms = c.moves({square: s, verbose: true});
            if (ms.length) dests[s] = ms.map(m => m.to);
        });
        return dests;
    }
    function getColor(m) {
        return m === 'w' ? 'white' : 'black';
    }
    function removeHeaders(pgn) {
        let splitPgn = pgn.split(']\n\n'),
            moves = splitPgn[splitPgn.length - 1];
        if (splitPgn.length === 1) { // If there are no moves
            return '';
        }
        if (moves.startsWith('1. ...')) {
            let spliced = moves.split('');
            spliced.splice(0,6,'1...');
            return spliced.join('');
        }
        return moves;
    }
    function changeTurn(c,cground) {
        return (o, d) => {
            c.move({from: o, to: d});
            cground.set({
                turnColor: getColor(c.turn()),
                movable: {
                  color: getColor(c.turn()),
                  dests: toDests(c)
                }
            });
            document.getElementById('pgn').value = removeHeaders(c.pgn());
        }
    }
    tools.undo.addEventListener('click',()=>{
        chess.undo();
        cg.set({
            fen: chess.fen(),
            turnColor: getColor(chess.turn()),
            movable: {
              color: getColor(chess.turn()),
              dests: toDests(chess)
            }
        });
        document.getElementById('pgn').value = removeHeaders(chess.pgn());
    });
});
