document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    
    // Загрузка статей
    fetch('api/articles.php', {
        headers: { 'Authorization': `Bearer ${token}` }
    })
    .then(response => response.json())
    .then(articles => {
        const list = document.getElementById('articles-list');
        articles.forEach(article => {
            list.innerHTML += `
                <div class="article">
                    <h3>${article.title}</h3>
                    <p>${article.content}</p>
                </div>
            `;
        });
    });
});