<?php
echo "Paso 1/3: Adjustando ruta wp-content<br>\n";
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\/\.\.\/\.\.\/wp-content/\/wp-content/g' {} \;");
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\/\.\.\/wp-content/\/wp-content/g' {} \;");
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\/wp-content/\/wp-content/g' {} \;");

echo "Paso 2/3: Adjustando ruta wp-includes<br>\n";
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\/\.\.\/\.\.\/wp-includes/\/wp-includes/g' {} \;");
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\/\.\.\/wp-includes/\/wp-includes/g' {} \;");
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\/wp-includes/\/wp-includes/g' {} \;");
exec("find /var/www/dev-static/html -type f -exec sed -i 's/\.\\\\\/wp-includes/\\\\\/wp-includes/g' {} \;");

echo "Paso 3/3: Encoding head<br>\n";
exec("find /var/www/dev-static/html -type f -exec sed -i 's/<head>/<head><meta charset=\"utf-8\"><meta http-equiv=\"Content-type\" content=\"text\/html; charset=utf-8\">/g' {} \;");

echo "Pasos completados";
