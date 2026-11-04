document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-editar-trilho').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const editId = document.getElementById('editTrilhoId');
            const editNome = document.getElementById('editTrilhoNome');
            const editLocal = document.getElementById('editTrilhoLocal');

            if (editId) editId.value = btn.getAttribute('data-id') || '';
            if (editNome) editNome.value = btn.getAttribute('data-nome') || '';
            if (editLocal) editLocal.value = btn.getAttribute('data-local') || '';
        });
    });

    document.querySelectorAll('.btn-excluir-trilho').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const delId = document.getElementById('delTrilhoId');
            const delNome = document.getElementById('delTrilhoNome');

            if (delId) delId.value = btn.getAttribute('data-id') || '';
            if (delNome) delNome.textContent = btn.getAttribute('data-nome') || '';
        });
    });
});

