// admin.js — manejo de eliminación en panel administrativo

document.addEventListener('DOMContentLoaded', () => {
  // delete handlers
  document.querySelectorAll('.btn-del').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const id = btn.dataset.id;
      if (!id) return;
      const confirmMsg = `¿Eliminar el mensaje #${id}? Esta acción no se puede deshacer.`;
      if (!confirm(confirmMsg)) return;

      fetch('assets/php/delete_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
      }).then(r => r.json()).then(data => {
        if (data && data.success) {
          const row = document.querySelector(`tr[data-id="${id}"]`);
          if (row) {
            row.style.transition = 'opacity 220ms ease, transform 220ms ease';
            row.style.opacity = '0';
            row.style.transform = 'translateY(6px)';
            setTimeout(() => row.remove(), 260);
          }
        } else {
          alert(data && data.message ? data.message : 'Error al eliminar');
        }
      }).catch(err => {
        console.error(err);
        alert('Error de red al eliminar.');
      });
    });
  });

  // view details handlers
  document.querySelectorAll('.btn-view').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const id = btn.dataset.id;
      const row = document.querySelector(`tr[data-id="${id}"]`);
      if (!row) return;
      const cells = row.querySelectorAll('td');
      const nombre = cells[1] ? cells[1].innerText.trim() : '';
      const email = cells[2] ? cells[2].innerText.trim() : '';
      const mensaje = cells[3] ? cells[3].innerText.trim() : '';
      const fecha = cells[4] ? cells[4].innerText.trim() : '';
      const text = `Mensaje #${id}\nDe: ${nombre} <${email}>\nFecha: ${fecha}\n\n${mensaje}`;
      // usar dialog simple
      alert(text);
    });
  });

});
