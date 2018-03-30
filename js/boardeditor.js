let turn = 'white';

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
    flip: document.getElementById('flip'),
    analyze: document.getElementById('analyze'),
    initial: document.getElementById('initial'),
    color: document.getElementById('color'),
    empty: document.getElementById('empty'),
    clearSelection: document.getElementById('clrSelect')
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
    document.getElementById('fen').value = cg.getFen();
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
