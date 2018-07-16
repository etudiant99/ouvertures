<?php
$this->titre = "Nouvelle ouvertures";

?>

<form>
    <input type="hidden" name="action" value="ecrireOuverture" />
    <input type="hidden" name="type" value="<?php echo $idtype; ?>" />
    <label>Ouverture</label>
    <input type="text" name="ouverture" required /><br />
    <input type="submit" value="Ok" />
</form>

