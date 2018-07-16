<?php
$this->titre = "Nouvelle ouvertures";

?>

<h3><?php echo $nomtype; ?></h3>
<form>
    <input type="hidden" name="action" value="ecrireOuverture" />
    <input type="hidden" name="type" value="<?php echo $idtype; ?>" />
    <label>Ouverture</label>
    <input type="text" name="ouverture" required /><br />
    <input type="submit" value="Ok" />
</form>

