document.querySelectorAll('.mark-read').forEach(el=>{
    el.addEventListener('click',()=>{
        el.parentElement.submit();
    });
});
