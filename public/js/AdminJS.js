document.body.onclick = function(e) {   //when the document body is clicked
    if (window.event) {
        e = event.srcElement;           //assign the element clicked to e (IE 6-8)
    }
    else {
        e = e.target;                   //assign the element clicked to e
    }

    if (e.className && e.className.indexOf('delete-user') != -1) {
        if(confirm("Estas seguro?")){
            var id = e.getAttribute('data-id');


            window.location.replace(`/admin/users/delete/${id}`, {
                method: 'DELETE'
            });

        }
    }
    if (e.className && e.className.indexOf('delete-tag') != -1) {
        if(confirm("Estas seguro?")){


            window.location.replace(`/admin/tags/delete/${id}`, {
                method: 'DELETE'
            });

        }
    }
    if (e.className && e.className.indexOf('delete-post') != -1) {
        if(confirm("Estas seguro?")){



            window.location.replace(`post/delete/${id}`, {
                method: 'DELETE'
            });

        }
    }
}

