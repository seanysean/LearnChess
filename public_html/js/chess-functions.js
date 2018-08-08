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
    let pieces = {};
    let piece = g.state.pieces[key];
    if (piece && piece.role == 'pawn') {
        pieces[key] = {
            color: piece.color,
            role: role,
            promoted: true
        };
        g.setPieces(pieces);
    }
}
function openPromoteOptions(board,square,cg) {
    // Requires popupjs
    return new Promise(r=>{
        const info = {
            html: `<button data-promote="queen"></button>
            <button data-promote="rook"></button>
            <button data-promote="bishop"></button>
            <button data-promote="knight"></button>`
        }
        const options = new Popup('custom',info);
        options.addClass('promotion-popup');
        document.body.removeChild(overlay);
        board.appendChild(overlay);
        overlay.style.position = 'absolute';
        options.open();
        options.el.querySelectorAll('button').forEach(btn=>{
            btn.addEventListener('click',()=>{
                const p = btn.getAttribute('data-promote');
                promote(cg,square,p);
                options.close();
                let promotionPiece = p.split('')[0];
                r(promotionPiece);
            });
        });
    });
}
