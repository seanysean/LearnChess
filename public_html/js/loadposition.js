function loadPosition(el,fen) {
    const getTurn = fen.split(' ')[1] === 'w' ? 'white': 'black';
    const config = {
        viewOnly: true,
        fen: fen,
        coordinates: false,
        orientation: getTurn,
        movable: {
            enabled: false
        }
    };
    Chessground(el,config);
}
const puzzlePreviews = document.querySelectorAll('[data-fen]');
puzzlePreviews.forEach(e=>{
    const fen = e.getAttribute('data-fen');
    loadPosition(e,fen);
});
