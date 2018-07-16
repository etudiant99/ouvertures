<?php
require_once 'Modele/Echiquier.php';
$echiquier = new Echiquier;

$this->titre = "Coups variante";
$idvariante = $_GET['id'];
$lettres = array('a','b','c','d','e','f','g','h');
$lastmove = '';
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

?>

<h4><?= $nomouverture ?></h4>
<h5><?= $nomvariante ?></h5>
<h6>[<?= $type ?>]</h6>
<br />

<div class="titre">
    <?php
    if ($totalcoups > 0)
    {
    ?>
    <div class="coupsdroite">
        <?php
        $i = 0;
        foreach($coups as $item)
        {
            if($i == $totalcoups-1)
                $bg = 'color: rgb(0, 0, 255)';
            else
                $bg = '';
            $i++;
            
            if ($i%2 == 0)
            {
                $truc = '<span style="width: 70px; text-align: left; '.$bg.'">'.$item.'</span>';
                echo $truc.'<br />';
            }
            else
            {
                $n = array();
                $truc = '<span style="width: 70px; text-align: left; '.$bg.'">'.$item.'</span>';
                $n = $echiquier->parseInt($i/2)+1;
                echo $n.'. '.$truc.'&nbsp;&nbsp;';
            } 
        }
        ?>
    </div>
    <?php } ?>
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
            
            $lapiece = $echiquier->trouve($position[$i]);
            $imagepiece = $lapiece->image();
            $couleurcase = $lapiece->Couleur($position[$i]);
            $contenucase = '<div style="background-color: '.$bgcolor.';" class="unecase">'.$imagepiece.'</div>';
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
    <a href="?action=coupvariante&amp;id=<?php echo $idvariante; ?>&amp;move=debut&amp;f=<?php echo $flip ?>"><img src="./Contenu/images/icons/set_first.png" border="0"/></a>
    <a href="?action=coupvariante&amp;id=<?php echo $idvariante; ?>&amp;move=precedent&amp;f=<?php echo $flip ?>&amp;t=<?php echo $totalcoups ?>"><img src="./Contenu/images/icons/set_previous.png" border="0"/></a>
    <a href="?action=coupvariante&amp;id=<?php echo $idvariante; ?>&amp;move=suivant&amp;t=<?php echo $totalcoups ?>&amp;f=<?php echo $flip ?>"><img src="./Contenu/images/icons/set_next.png" border="0"/></a>
    <a href="?action=coupvariante&amp;id=<?php echo $idvariante; ?>&amp;move=fin&amp;f=<?php echo $flip ?>"><img src="./Contenu/images/icons/set_last.png" border="0"/></a>&nbsp;
    <a href="?action=coupvariante&amp;id=<?php echo $idvariante; ?>&amp;move=tourner&amp;f=<?php echo $flip ?>&amp;t=<?php echo $totalcoups ?>"><img src="./Contenu/images/icons/flip.png" border="0"/></a>
</div>