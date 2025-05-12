function paginateAdminTables(pageSize) {
  document.querySelectorAll('.paginated-table').forEach(table => {
    const rows = Array.from(table.tBodies[0].rows);
    const pageCount = Math.ceil(rows.length / pageSize);
    const pager = table.parentElement.querySelector('.pagination');
    if (pageCount < 2) return;
    // erstelle Seiten-Buttons
    for (let i = 1; i <= pageCount; i++) {
      const li = document.createElement('li');
      li.className = 'page-item';
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener('click', e => {
        e.preventDefault();
        showPage(table, rows, pageSize, i - 1);
        pager.querySelectorAll('.page-item').forEach(x=>x.classList.remove('active'));
        li.classList.add('active');
      });
      pager.appendChild(li);
    }
    // erste Seite anzeigen + ersten Button aktiv setzen
    showPage(table, rows, pageSize, 0);
    pager.querySelector('li').classList.add('active');
  });
}

function showPage(table, rows, pageSize, pageIndex) {
  rows.forEach((r,i)=> r.style.display = (i >= pageIndex*pageSize && i < (pageIndex+1)*pageSize) ? '' : 'none');
}
