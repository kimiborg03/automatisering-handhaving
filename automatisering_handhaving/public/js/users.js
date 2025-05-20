document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('userSearch');
    const usersTable = document.getElementById('usersTable');
    const loadMoreBtn = document.getElementById('loadMore');

    let currentPage = 1;
    let currentSearch = '';

    function fetchUsers(page, append = false) {
        fetch(`?page=${page}&search=${encodeURIComponent(currentSearch)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            if (append) {
                usersTable.insertAdjacentHTML('beforeend', data);
            } else {
                usersTable.innerHTML = data;
            }

            currentPage = page;

            if (!data.includes('<tr>')) {
                if (document.getElementById('loadMore')) {
                    document.getElementById('loadMore').remove();
                }
                return;
            }

            if (!document.getElementById('loadMore')) {
                const btn = document.createElement('button');
                btn.id = 'loadMore';
                btn.className = 'btn btn-primary mt-3';
                btn.textContent = 'Meer laden';
                btn.dataset.nextPage = currentPage + 1;
                btn.addEventListener('click', loadMoreUsers);
                usersTable.parentElement.insertAdjacentElement('afterend', btn);
            } else {
                loadMoreBtn.dataset.nextPage = currentPage + 1;
            }
        });
    }

    function loadMoreUsers() {
        const nextPage = parseInt(this.dataset.nextPage);
        fetchUsers(nextPage, true);
    }

    searchInput.addEventListener('input', function () {
        currentSearch = this.value;
        currentPage = 1;
        fetchUsers(1, false);
    });

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreUsers);
    }
});