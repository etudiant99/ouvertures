<?php $this->titre = "Ouvertures"; ?>

<form method="get">
    <input type="hidden" name="action" value="ouvertures" />
    <select name="type">
        <?php foreach ($types as $type): ?>
            <option value="<?php echo $type->getId(); ?>"><?php echo $type->getType(); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Ok" />
</form>