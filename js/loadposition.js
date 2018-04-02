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
    const cg = Chessground(el,config);
}
