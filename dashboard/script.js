const unabsentedList = document.getElementById('unabsented-list');
const paginationControls = document.getElementById('pagination-controls');
const items = Array.from(unabsentedList.children);
const itemsPerPage = 10; // Sesuaikan jumlah item per halaman
let currentPage = 1;

function displayPage(page) {
    // Bersihkan daftar
    unabsentedList.innerHTML = '';
    const start = (page - 1) * itemsPerPage;
    const end = page * itemsPerPage;
    const pageItems = items.slice(start, end);

    // Tampilkan item di halaman saat ini
    pageItems.forEach(item => unabsentedList.appendChild(item));
}

function createPaginationControls() {
    // Hitung total halaman
    const totalPages = Math.ceil(items.length / itemsPerPage);
    paginationControls.innerHTML = '';

    // Buat kontrol pagination
    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.classList.add('page-item');
        const a = document.createElement('a');
        a.classList.add('page-link');
        a.textContent = i;
        a.addEventListener('click', () => {
            // Tampilkan halaman yang dipilih
            currentPage = i;
            displayPage(i);
        });
        li.appendChild(a);
        paginationControls.appendChild(li);
    }
}

// Inisialisasi halaman pertama dan kontrol pagination
displayPage(currentPage);
createPaginationControls();
