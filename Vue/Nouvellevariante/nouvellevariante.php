<?php
$this->titre = "Ouvertures";
?>

<form>
    <input type="hidden" name="action" value="ecrireVariante" />
    <input type="hidden" name="ouverture" value="<?php echo $idouverture; ?>" />
    <label>Variante</label>
    <input type="text" name="variante" required /><br />
    <button type="submit">Ok</button>
</form>