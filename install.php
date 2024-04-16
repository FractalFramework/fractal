<?php
//fractal
shell_exec('wget -P '.__DIR__.' http://logic.ovh/fractal.tar.gz');
shell_exec('tar -zxvf '.__DIR__.'/fractal.tar.gz');
shell_exec('chmod -R 755 '.__DIR__);

?>