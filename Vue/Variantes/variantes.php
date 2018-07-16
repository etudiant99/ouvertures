<?php
$this->titre = "Variantes";
$idOuverture = $_GET['id'];

$coupsouverture = $ouverture->getListecoups();
?>

<form class="agauche">
    <input type="hidden"   name="action"  value="nouvellevariante" />
    <input type="hidden"   name="ouverture"  value="<?php echo $_GET['id'] ?>" />
    <button name="nouvellevariante" value="yes">Ajouter variante</button>
</form>

<h1><?= $ouverture->getOuverture(); ?></h1>
<h4><?= $ouverture->getType(); ?></h4>

<div class="container">
<div class="row">
<div class="col-sm-4">
    <br />
    <h6><a href="?action=echiquier&id=<?php echo $idOuverture; ?>">Coups de base</a></h6>
    <table>
        <tr><th></th><th>Blancs</th><th>Noirs</th></tr>
        <?php
        $compteur = 0;
        foreach ($coupsouverture as $coup)
        {
            $coup = explode(" ",$coup);
            $coupBlanc = $coup[0];
            if (isset($coup[1]))
                $coupNoir = $coup[1];
            else
                $coupNoir = '';
            $compteur++;
            ?>
            <tr>
                <td><?= $compteur ?></td>
                <td><?= $coupBlanc ?></td>
                <td><?= $coupNoir ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<br />

<?php
if ($variantes != null)
{
    foreach ($variantes as $variante)
    {
        ?>
        <div class="col-sm-4">
            <br />
            <h6><a href="?action=coupvariante&id=<?php echo $variante->getId(); ?>"><?= $variante->getVariante() ?></a><a href="?action=variantes&id=<?php echo $_GET['id'] ?>&but=effacer&variante=<?php echo $variante->getId(); ?>"onclick="if(!confirm('Voulez-vous vraiment effacer  <?php echo $variante->getVariante() ?> ?')) return false;"><img src="Contenu/images/icons/effacer.png" width="20" height="15" /></a></h6>
            <?php
            $coups = $variante->getListecoups();
            $compteur = 0;
            if ($coups != null)
            {
            ?>
            <table>
                <tr><th></th><th>Blancs</th><th>Noirs</th></tr>
            <?php
            foreach ($coups as $coup)
            {
                $compteur++;
                if (strlen($coup) > 6 )
                    $coups = explode(" ",$coup);
                else
                    $coups = null;
                ?>
                <tr>
                    <td><?= $compteur ?></td>
                    <td><?php if (!isset($coups)) echo $coup; else echo $coups[0]; ?></td>
                    <td><?php if (isset($coups[1])) echo $coups[1]; ?></td>
                </tr>
                <?php
            }
            ?>
            </table>
        </div>
        <br />
        <?php
        }
    }
}
?>
</div>
</div>