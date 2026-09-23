document.addEventListener('DOMContentLoaded', function () {
    var confirmDelete = document.getElementById('confirmDelete');
    if (confirmDelete) {
        confirmDelete.addEventListener('click', function () {
            var f = document.getElementById('deleteForm');
            if (f) f.submit();
        });
    }

    var confirmUpdate = document.getElementById('confirmUpdate');
    if (confirmUpdate) {
        confirmUpdate.addEventListener('click', function () {
            var initial = document.getElementById('initialAcesso');
            var selected = document.querySelector('input[name="acesso"]:checked');
            var uf = document.getElementById('updateForm');
            var accessForm = document.getElementById('accessForm');
            var initialVal = initial ? initial.value : null;
            var selectedVal = selected ? selected.value : null;

            // If access changed, submit only accessForm with action=change_access
            if (selectedVal !== null && initialVal !== null && selectedVal !== initialVal && accessForm) {
                var inA = accessForm.querySelector('input[name="acesso"]');
                if (inA) inA.value = selectedVal;
                accessForm.submit();
                return;
            }

            // otherwise submit full update form
            if (uf) uf.submit();
        });
    }
});
