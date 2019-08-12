$('.mark-read',true).forEach(el=>{
    el.addEventListener('click',()=>{
        el.parentElement.submit();
    });
});
