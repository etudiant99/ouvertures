<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $titre ?></title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" />
        <link href="Contenu/style.css" rel="stylesheet" />
    </head>

    <body>
        <?php
        $prive = array('ouverture' => 'Ouvertures');
        ?>
        <header>
            <div class="petitecran">
                <br />ouvertures
            </div>
        </header>
        <br />
        <div class="petitecran">
            <nav>
                <div class="espace">
                    Espace membre
                </div>
                <ul id="espacepublic">
                    <?php
                    foreach ($prive as $key => $value):
                    ?>
                        <li><a href="?action=<?php echo $key ?>"><?php echo $value ?></a></li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            </nav>
            <section>
                <?= $contenu ?>
            </section>
        </div>
    </body>
</html