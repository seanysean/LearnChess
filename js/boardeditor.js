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
        updateFEN();
        const pieces = cg.state.pieces;
        const selectedPiece = document.querySelector('.selectedPiece').getAttribute('data-piece').split(' ');
        pieces[s] = {
            color: selectedPiece[0],
            role: selectedPiece[1]
        }
        cg.setPieces(pieces);
    }
}
function updateFEN() {
    document.getElementById('fen').value = cg.getFen();
}

const dataPieces = document.querySelectorAll('[data-piece]'),
      clrSelect = document.getElementById('clrSelect'),
      fenInput = document.getElementById('fen')
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
clrSelect.addEventListener('click',()=>{
    document.getElementsByClassName('selectedPiece')[0].classList.remove('selectedPiece');
    selection = false;
});
fenInput.addEventListener('keyup',()=>{
    cg.set({
        fen: fenInput.value
    });
});
