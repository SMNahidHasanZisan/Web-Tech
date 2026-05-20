function searchContents() {
    var q = document.getElementById('searchText').value.trim();
    var categoryId = document.getElementById('searchCategory').value;
    var subcategory = document.getElementById('searchSubcategory');
    var subcategoryId = subcategory ? subcategory.value : '';
    var selectedCategory = subcategoryId || categoryId;
    var url = '../api/search.php?q=' + encodeURIComponent(q) + '&category_id=' + encodeURIComponent(selectedCategory);

    fetch(url)
        .then(function (response) { return response.json(); })
        .then(function (result) {
            var box = document.getElementById('searchResults');
            box.innerHTML = '';

            result.data.forEach(function (item) {
                box.innerHTML += '<div class="card">' +
                    '<h3>' + escapeHtml(item.title) + '</h3>' +
                    '<p>' + escapeHtml(item.description) + '</p>' +
                    '<p><strong>Category:</strong> ' + escapeHtml(item.category_name) + '</p>' +
                    '<a class="button" href="download.php?id=' + item.id + '">Download</a>' +
                    '</div>';
            });

            if (result.data.length === 0) {
                box.innerHTML = '<p>No content found.</p>';
            }
        });
}

function updateSubcategoryFilter() {
    var categoryId = document.getElementById('searchCategory').value;
    var subcategory = document.getElementById('searchSubcategory');
    if (!subcategory) {
        return;
    }

    subcategory.value = '';
    Array.prototype.forEach.call(subcategory.options, function (option) {
        if (option.value === '') {
            option.hidden = false;
            return;
        }
        option.hidden = categoryId !== '' && option.getAttribute('data-parent') !== categoryId;
    });
}

function updateRequestStatus(id, status) {
    var data = new FormData();
    data.append('id', id);
    data.append('status', status);
    data.append('csrf_token', getCsrfToken());

    fetch('../api/request_status.php', {
        method: 'POST',
        body: data
    })
        .then(function (response) { return response.json(); })
        .then(function (result) {
            if (result.success) {
                document.getElementById('status-' + id).innerText = result.status;
            } else {
                alert(result.message);
            }
        });
}

function getCsrfToken() {
    var tag = document.querySelector('meta[name="csrf-token"]');
    return tag ? tag.getAttribute('content') : '';
}

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, function (char) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char];
    });
}

var requestForm = document.getElementById('requestForm');
if (requestForm) {
    requestForm.addEventListener('submit', function (event) {
        event.preventDefault();

        var title = requestForm.content_title.value.trim();
        var category = requestForm.category_requested.value;

        if (title === '' || category === '') {
            document.getElementById('requestMessage').innerText = 'Content title and category are required.';
            return;
        }

        fetch('../api/request_add.php', {
            method: 'POST',
            body: new FormData(requestForm)
        })
            .then(function (response) { return response.json(); })
            .then(function (result) {
                document.getElementById('requestMessage').innerText = result.message;
                if (result.success) {
                    requestForm.reset();
                }
            });
    });
}
