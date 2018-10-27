const editExplanationHTML = document.querySelectorAll('.edit-explanation');
editExplanationHTML.forEach(el=>{
    const info = {
        title: 'Edit puzzle ' + el.getAttribute('data-puzzle') + ' explanation',
        text: 'Edit grammar, spelling, etc...',
        yes: '<i class="fa fa-pencil"></i> Edit',
        no: 'Cancel',
        value: el.getAttribute('data-explanation')
    },
    events = {
        yes() {
            myPopup.close();
            let v = myPopup.input.value;
            const dataIds = Array.from(document.querySelectorAll('[data-id]'));
            const filtered = dataIds.filter(id=>{
                return el.getAttribute('data-puzzle') === id.getAttribute('data-id');
            });
            filtered[0].innerHTML = v;
            filtered[1].value = v;
        },
        no() {
            myPopup.close();
        }
    };
    let myPopup = new Popup('prompt',info,events);
    el.addEventListener('click',()=>{
         myPopup.open();
    });
});

const submitButton = document.querySelectorAll('a.form-submit');
submitButton.forEach(el=>{
    let acceptOrDelete = el.getAttribute('data-delete') === '0' ? 'accepting' : 'deleting';
    const info = {
        title: `Confirm ${acceptOrDelete} puzzle`,
        text: 'This will notify the puzzle creator',
        yes: '<i class="fa fa-check"></i> Confirm',
        no: 'Cancel'
    },
    events = {
        yes() {
            myPopup.close();
            el.parentElement.parentElement.submit();
        },
        no() {
            myPopup.close();
        }
    };
    let myPopup = new Popup('confirm',info,events);
    el.addEventListener('click',()=>{
        myPopup.open();
        console.log(el);
    });
});
