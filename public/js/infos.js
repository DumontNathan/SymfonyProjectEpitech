var info = document.getElementById('movies');

if(info)
{
    info.addEventListener('click', e => {
    
        if(e.target.className === "btn btn-success info")
        {
            const filmId = e.target.getAttribute('data-id');
            fetch(`/film/${filmId}`).then(res => window.location.replace(`http://127.0.0.1:8000/film/${filmId}`));
        }
    });
}