const start = document.getElementById('start'),
      overlay = document.getElementById('overlay'),
      square = document.getElementById('square'),
      c1 = document.getElementById('c1'),
      c2 = document.getElementById('c2');
const config = {
    coordinates: false,
    draggable: {
        enabled: false
    },
    movable: {
        enabled: false
    },
    selectable: {
        enabled: false
    },
    events: {
        select: sel
    }
}, cg = Chessground(document.getElementById('board'),config);
let currentSquare = '',
    score = 0,
    time = 0;

function sel(key) {
    if (key === currentSquare) {
        score++;
        c2.innerHTML = `<p><i class="fa fa-check"></i> Correct</p><span class="score">Your score: <span id="score">${score}</span></span>`;
        c2.classList.add('right');
        c2.classList.remove('wrong');
    } else {
        c2.innerHTML = `<p><i class="fa fa-close"></i> Incorrect</p><span class="score">Your score: <span id="score">${score}</span></span>`;
        c2.classList.add('wrong');
        c2.classList.remove('right');
    }
    nextSquare();
}
function nextSquare() {
    const letters = ['a','b','c','d','e','f','g','h'],
          nums = [1,2,3,4,5,6,7,8],
          letter = letters[Math.floor(8 * Math.random())],
          num = nums[Math.floor(8 * Math.random())];
    currentSquare = letter + num;
    square.innerHTML = currentSquare;
}
start.addEventListener('click',()=>{
    overlay.classList.add('transparent');
    nextSquare();
    c1.style.opacity = 0;
    setTimeout(()=>{
        c1.style.display = 'none';
        c2.style.opacity = 1;
        c2.style.filter = 'blur(0)';
    },250);
    const countDown = setInterval(()=>{
        time++;
        document.getElementById('time').style.width = Math.round(time * 3.33) + '%';
        if (time === 31) {
            overlay.classList.remove('transparent');
            square.innerHTML = `Your score is ${score}`;
            clearInterval(countDown);
            if (!loggedin) {
                square.innerHTML += '<p class="notice">Log in to save your score next time</p>';
            } else {
                const xhr = new XMLHttpRequest(),
                      url = '/coordinates/savescore',
                      data = `score=${score}`;
                xhr.responseType = 'json';
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === xhr.DONE) {
                        if (xhr.response.success) {
                            square.innerHTML += '<p class="notice"><i class="fa fa-check"></i> Score saved</p>';
                        } else {
                            square.innerHTML += '<p class="notice"><i class="fa fa-close"></i> Score wasn\'t saved</p>';
                        }
                        const btn = document.createElement('button');
                        btn.classList = 'flat-button';
                        btn.id = 'reload';
                        btn.innerHTML = '<span><i class="fa fa-undo"></i> Reload</span>';
                        btn.addEventListener('click',()=>{window.location.reload();});
                        square.appendChild(btn);
                    }
                }
                xhr.open('POST',url);
                xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                xhr.send(data);
            }
        }
    },1000);
});
