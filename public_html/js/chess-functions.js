// Functions that go with the Chess.js and Chessground libraries.

function getColor(c) {
    return c === 'w' ? 'white':'black';
}
function toDests(c) {
    const dests = {};
    c.SQUARES.forEach(s => {
        const ms = c.moves({square: s, verbose: true});
        if (ms.length) dests[s] = ms.map(m => m.to);
    });
    return dests;
}
function promote(g, key, role) {
    var pieces = {};
    var piece = g.state.pieces[key];
    if (piece && piece.role == 'pawn') {
        pieces[key] = {
            color: piece.color,
            role: role,
            promoted: true
        };
        g.setPieces(pieces);
    }
}
