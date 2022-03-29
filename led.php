
<html>
    <head>

    </head>
    <body>
            <form name="led-control" method="post" action="led.php">
                <button name="on" type="submit" value="on">Allumer</button>
		<button name="off" type="submit" value="off">Éteindre</button>
		<button name="test" type="submit" value="test">Tester l'antenne</button>
            </form>
            <?php
		$command_on = escapeshellcmd('python3 /var/www/html/henallux/lora_led_on.py');
		$command_off = escapeshellcmd('python3 /var/www/html/henallux/lora_led_off.py');
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
    </body>
</html>
