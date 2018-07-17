<?php
require_once 'Modele/Echiquier.php';
$echiquier = new Echiquier;

if($lastmove != '')
{
    $lmove = explode("-",$lastmove);
    $start = $lmove[0];
    $end = $lmove[1];
}
else
{
    $start = -1;
    $end = -1;
}
$titre = 'Variante: '.$nomvariante;
?>
<h3><?= $nomouverture ?></h3>
<h5><?= $titre ?></h5>
<br />

<div class="titre">
    <div class="coupsdroite">
        <?php
        if ($cell1 != -1 and $cell2 != -1)
        {
            $positiontemp = $position;
            $p = $position[$cell1];
            $lapiece = $echiquier->trouve($p);
            $coupvalide = $lapiece->legal($positiontemp,$cell1,$cell2,$derniercoup);
            $lecoup = $echiquier->moveToText($cell1).$echiquier->moveToText($cell2);
            if ($coupvalide)
            {
                ?>
                <div class="acceptercoup">
                    <form>
                        <input type="hidden"   name="action"  value="ecrireVariante" />
                        <input type="hidden"   name="ouverture"  value="<?php echo $idouverture; ?>" />
                        <input type="hidden"   name="variante"  value="<?php echo $nomvariante; ?>" />
                        <input type="hidden"   name="lecoup"  value="<?php echo $lecoup ?>" />
                        <input type="submit" value="Jouer" /><?php echo ' '.$lecoup ?>
                    </form>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
<div class="leschiffres">
    <div>
        <?php
        if ($flip == false)
            for ($j=8;$j>0;$j--)
            {
                ?>
                <div class="chiffres"><?php echo $j; ?></div>
            <?php
            }
        else
            for ($j=1;$j<9;$j++)
            {
                ?>
                <div class="chiffres"><?php echo $j; ?></div>
            <?php
            }
    ?>
    </div>
</div>
<div class="echiquier">
    <?php
    for($ligne=0; $ligne<8; $ligne++)
    {
        for ($colonne=0;$colonne<8;$colonne++)
        {
            if ($flip == false)
                $i = (7-$ligne)*8 + $colonne;
            else
                $i = $ligne*8+(7-$colonne);
            
            if ($couleur == 1)
                $bgcolor = "white";
            else
                $bgcolor = "lightblue";
                
            // Pour le lastmove
            if ($i == $start || $i == $end)
                $bgcolor = "#baffbf";
            
            // Pour les endroits ou l'on peut jouer la piece
            if (isset($positions) && $cell2 == -1)
                for ($po=0;$po<count($positions);$po++)
                {
                    if ($i == $positions[$po])
                        $bgcolor = "#99fff3";   
                }
                        
            // Pour indiquer ou l'on veut déplacer une pièce
            if ($i == $cell1 or $i == $cell2)
                $bgcolor = "#ffff35";

            $lapiece = $echiquier->trouve($position[$i]);
            $imagepiece = $lapiece->image();
            $couleurcase = $lapiece->Couleur($position[$i]);
            $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase">'.$imagepiece.'</div>';
            if ($cliquable)
            {
                if ($position[$i] != '' and $couleurcase == $trait)
                    if ($cell1 == -1)
                    {
                        $lapiece = $echiquier->trouve($position[$i]);
                        $quantite = $lapiece->nbEndroitsPossibles($position,$i,$trait,$derniercoup);
                        if ($quantite > 0)
                            $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase"><a href="index.php?action=ecrireVariante&amp;ouverture='.$idouverture.'&amp;variante='.$nomvariante.'&amp;depart='.$i.'">'.$imagepiece.'</a></div>';
                    }
                if ($cell1 != -1)
                    for ($po=0;$po<count($positions);$po++)
                    {
                        if ($positions[$po]  == $i)
                            $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase"><a href="index.php?action=ecrireVariante&amp;ouverture='.$idouverture.'&amp;variante='.$nomvariante.'&amp;depart='.$cell1.'&amp;arrivee='.$i.'">'.$imagepiece.'</a></div>';
                        }
            }
            echo $contenucase;
            
            $couleur = -$couleur;
        }
        $couleur = -$couleur;
    }
    ?>
</div>
<div class="leslettres">
    <?php
    if ($flip == false)
        for ($j=0;$j<8;$j++)
        {
            ?>
            <div class="lettres"><?php echo $lettres[$j]; ?></div>
            <?php
        }
    else
        for ($j=7;$j>=0;$j--)
        {
            ?>
            <div class="lettres"><?php echo $lettres[$j]; ?></div>
            <?php
        }
    ?>
</div>
<div class="piecesblanchessmangees">
    <?php
    if (isset($mangeaille))
    {
        foreach($mangeaille['blancs'] as $item)
            echo $item;
        if (count($mangeaille['blancs']) == 0)
            echo '<img src="./Contenu/images/vide_21.gif">';
    }
    else
        echo '<img src="./Contenu/images/vide_21.gif">';
    ?>
</div>
<div class="piecesnoiressmangees">
    <?php
    if (isset($mangeaille))
    {
        foreach($mangeaille['noirs'] as $item)
            echo $item;
        if (count($mangeaille['noirs']) == 0)
            echo '<img src="./Contenu/images/vide_21.gif">';
    }
    else
        echo '<img src="./Contenu/images/vide_21.gif">';
    ?>
</div>

<div class="centrer">
    <form>
        <input type="hidden"   name="action"  value="ecrireVariante" />
        <input type="hidden"   name="ouverture"  value="<?php echo $idouverture; ?>" />
        <input type="hidden"   name="variante"  value="<?php echo $nomvariante; ?>" />
        <button name="enregistrer" value="yes">Enregistrer</button>
        <button name="effacer" value="yes">Effacer</button>
    </form>
</div>
