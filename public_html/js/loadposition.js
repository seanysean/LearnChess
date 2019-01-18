function loadPosition(el,fen,size) {
    if (size) {
        el.style.height = size + 'px';
        el.style.width = size + 'px';
    }
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
