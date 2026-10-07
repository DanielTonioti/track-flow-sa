<?php
$backButtonId ??= $backButtonId ?? 'voltarhub';
$backButtonText ??= $backButtonText ?? 'Voltar';
?>
<button id="<?= htmlspecialchars($backButtonId, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-danger back-buttom">
    <?= htmlspecialchars($backButtonText, ENT_QUOTES, 'UTF-8') ?> </button>