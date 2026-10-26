<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='main.css'>
</head>
<body>
     <form method="get" action=".">
        <label>Cep:
        <input name="cep" type="text" id="cep" value="" size="10" maxlength="9" onblur="pesquisacep(this.value);" /></label><br/>
        <label>Rua:
        <input name="rua" type="text" id="rua" size="60" /></label><br/>
        <label>Bairro:
        <input name="bairro" type="text" id="bairro" size="40" /></label><br/>
        <label>Cidade:
        <input name="cidade" type="text" id="cidade" size="40" /></label><br/>
        <label>Estado:
        <input name="uf" type="text" id="uf" size="2" /></label><br/>
      </form>
      <script src="../scripts/script_cep.js"></script>
</body>
</html>