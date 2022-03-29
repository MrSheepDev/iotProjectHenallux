
<html>
    <head>

    </head>
    <body>
            <form name="led-control" method="post" action="leddb.php">
                <button name="on" type="submit" value="on">Allumer</button>
		<button name="off" type="submit" value="off">Éteindre</button>
		<button name="test" type="submit" value="test">Tester l'antenne</button>
	    </form>
	    <section class="py_resp">
            <?php
		$command_on = escapeshellcmd('python3 /var/www/html/henallux/led_on_db.py');
		$command_off = escapeshellcmd('python3 /var/www/html/henallux/led_off_db.py');
		$test = escapeshellcmd('python3 /var/www/html/henallux/check.py');

                if(isset($_POST['on'])){
			echo shell_exec($command_on);
			echo "<br>";
			echo "Allumé";
			echo "<br>";
                } elseif (isset($_POST['off'])){
			echo shell_exec($command_off);
                        echo "<br>";
			echo "Éteint";
                        echo "<br>";
		} elseif (isset($_POST['test'])){
			echo shell_exec($test);
                        echo "<br>";
			echo "testé";
                        echo "<br>";
		}
	    ?>
	    </section>
	    <section class="get_db">
	    <?php

		try{
			//connexion à la bdd
			$bdd = new PDO('mysql:host=localhost;dbname=phpmyadmin;charset=utf-8', 'phpmyadmin', 'raspberry');
		} catch (Exception $e {
			//récupérer l'exception
			die('Erreur : '.$e->getMessage());
		}

		$reponse = $bdd->query('SELECT * FROM lumiere');

		while ($donnees = $reponse->fetch()){
			if ($donnees['mesure'] == 0){
				$etat = "Eteint";
			} else if ($donnees['mesure'] == 1){
				$etat = "Allumé";
			}
			?>
				<p> Status : <?php echo $etat;?>      Temps : <?php echo $donnees['temps']; ?></p>
	    <?php
		}
		// Cloture la session SQL
		$reponse->closeCursor();
	    ?>
	    </section>
    </body>
</html>
