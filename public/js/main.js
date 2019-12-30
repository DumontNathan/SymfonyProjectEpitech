const unsubscribe = document.getElementById('delete-btn');

if(unsubscribe)
{
    unsubscribe.addEventListener('click', e => { 
        if(confirm('Are you sure you want to unsubscribe ? \nYou will lost your account and all your lists and favorite movies.'))
        {
            const id = e.target.getAttribute('data-id');
            fetch(`unsubscribe/${id}`, {
                method: 'DELETE'
            }).then(res => window.location.replace("http://127.0.0.1:8000")); 
        }
    });
}

