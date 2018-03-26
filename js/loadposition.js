function loadPosition(el,fen) {
    const config = {
        viewOnly: true,
        fen: fen,
        coordinates: false,
        movable: {
            enabled: false
        }
    };
    const cg = Chessground(el,config);
}
