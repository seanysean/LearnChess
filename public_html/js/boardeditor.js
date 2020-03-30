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
const cg = Chessground($('#cg'),config);
let selection = false;

function setPiece(s) {
    if (selection) {
        const pieces = cg.state.pieces;
        const selectedPiece = $('.selectedPiece').getAttribute('data-piece').split(' ');
        pieces[s] = {
            color: selectedPiece[0],
            role: selectedPiece[1]
        }
        cg.setPieces(pieces);
        updateFEN();
    }
}

const tools = {
    container: $('#t-cont'),
    flip: $('#flip'),
    analyze: $('#analyze'),
    initial: $('#initial'),
    color: $('#color'),
    empty: $('#empty'),
    clearSelection: $('#clrSelect'),
    undo: $('#undo')
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
    updateFEN();
});
tools.empty.addEventListener('click',()=>{
    cg.set({
        fen: '8/8/8/8/8/8/8/8'
    });
    updateFEN();
});
tools.clearSelection.addEventListener('click',()=>{
    $('.selectedPiece').classList.remove('selectedPiece');
    selection = false;
});

function updateFEN() {
    if (isNextStep) {
        return;
    }
    $('#fen').value = cg.getFen() + (turn === 'white' ? ' w' : ' b') + ' - - 0 1';
    tools.analyze.href = 'https://lichess.org/analysis/' + cg.getFen();
}

const dataPieces = $('[data-piece]',true),
      fenInput = $('#fen');
dataPieces.forEach(p=>{
    p.addEventListener('click',()=>{
        let selectedPiece = $('.selectedPiece');
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

const nextStep = $('#next');
nextStep.addEventListener('click',()=>{
    const cancel = $('#cancel');
    let hideLastMoveHighlight = false;
    if (selection) {
        $('.selectedPiece').classList.remove('selectedPiece');
        selection = false;
    }
    isNextStep = true;
    $('#fen-cont').style.display = 'none';
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
    $('#pgn-cont').style.display = 'block';
    $('#explain-cont').style.display = 'block';
    $('#submit').style.display = 'inline-block';
    cancel.style.display = 'inline-block';
    const spares = $('.spare',true);
    spares.forEach(el=>{
        el.classList.add('disabled');
    });
    const chess = new Chess(cg.getFen() + (turn === 'white' ? ' w' : ' b') + ' - - 0 1');
    $('#fen').value = chess.fen();
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
    function changeTurn(c,cground) {
        return async (o, d) => {
            const m = c.move({from: o, to: d, promotion: 'q'});
            if (m.flags.includes('p')) {
                c.undo();
                const waitForPromotion = await openPromoteOptions($('#cg'),m.to,cground,getColor(c.turn()));
                if (waitForPromotion) {
                    let x = c.move({ from: o, to: d, promotion: waitForPromotion });
                    $('#pgn').value = removeHeaders(c.pgn());
                }
            } else {
                $('#pgn').value = removeHeaders(c.pgn());
            }
            cground.set({
                turnColor: getColor(c.turn()),
                movable: {
                    color: getColor(c.turn()),
                    dests: toDests(c)
                }
            });
            if (hideLastMoveHighlight) {
                $('.last-move',true).forEach(e=>{
                    show(e);
                });
            }
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
        $('#pgn').value = removeHeaders(chess.pgn());
        if (!hideLastMoveHighlight) {
            $('.last-move',true).forEach(e=>{
                hide(e);
            });
        }
        hideLastMoveHighlight = true;
    });
    const info = {
        title: 'Are you sure you want to delete your work?',
        text: 'Click yes to go back to position setup.',
        yes: 'Yes',
        no: 'No'
    },
    events = {
        yes() {
            confirmCancel.close();
            window.location.href="new";
        },
        no() {
            confirmCancel.close();
        }
    };
    const confirmCancel = new Popup('confirm',info,events);
    cancel.addEventListener('click',()=>{
        confirmCancel.open();
    });
});
