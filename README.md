ServiredBundleDemo
==================

Demo Instalable de la pasarela de pago Servired 

######  Enlaces de interes ( require instalación ):

 1.  [Home Demo](http://demo.serviredbundle.com/demo/)
 2.  [Comprar producto](http://demo.serviredbundle.com/demo/product)
 3.  [Reintentar pago](http://demo.serviredbundle.com/demo/retrypayment)
 4.  [Tarjetas de credito de prueba](https://github.com/rafaelcalleja/ServiredBundle/blob/master/Resources/doc/test_credit_card.rst)
 5.  [Configuracion ejemplo](https://github.com/rafaelcalleja/ServiredBundle/blob/master/Resources/doc/config.dist.yml)
 6.  [Ejemplo Controllers](https://github.com/rafaelcalleja/ServiredBundleDemo/blob/master/src/Acme/DemoBundle/Controller/DemoController.php)
 7.  [Ejemplo Entidad Transaccion](https://github.com/rafaelcalleja/ServiredBundleDemo/blob/master/src/Acme/DemoBundle/Entity/Sale.php)
 

1) Instalación
--------------------------------

### Clonar el repositorio git
       $ mkdir /var/www/vhosts/demo.serviredbundle.com/httpdocs/ -p
       $ cd /var/www/vhosts/demo.serviredbundle.com/httpdocs/
       $ git clone git@github.com:rafaelcalleja/ServiredBundleDemo.git .
       
### Configurar la base de datos app/config/parameters.ini

       database_driver: pdo_mysql
       database_host: 127.0.0.1
       database_port: null
       database_name: demoservired
       database_user: USERNAME
       database_password: PASSWORD
       
### Crear base de datos
       
       $ app/console doctrine:database:create
       $ app/console doctrine:schema:update --force
       
### Configurar Apache (Ubuntu)

Editar el archivo hosts:

	$ sudo vi /etc/hosts

y añadir la línea siguiente:

	127.0.0.1   demo.serviredbundle.com

Configuramos un VirtualHost para el nuevo dominio

	$ sudo vi /etc/apache2/sites-enabled/demo.serviredbundle.com

con el siguiente contenido:

        <VirtualHost 0.0.0.0:80>
            ServerName demo.serviredbundle.com
            DocumentRoot /var/www/vhosts/demo.serviredbundle.com/httpdocs/web
            
            <Directory /var/www/vhosts/demo.serviredbundle.com/httpdocs>
                Order deny,allow
                Allow from all
                Options FollowSymLinks
                AllowOverride All
            </Directory>
        </VirtualHost>

Habilitamos el nuevo VirtualHost:

	$ sudo a2ensite demo.serviredbundle.com

Reiniciamos apache:

	$ sudo /etc/init.d/apache2 restart

### Configurar los permisos de app/cache y app/logs (Ubuntu)

       $ rm -rf app/cache/*
       $ rm -rf app/logs/*

       $ APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
       $ sudo setfacl -R -m u:$APACHEUSER:rwX -m u:`whoami`:rwX app/cache app/logs
       $ sudo setfacl -dR -m u:$APACHEUSER:rwX -m u:`whoami`:rwX app/cache app/logs

