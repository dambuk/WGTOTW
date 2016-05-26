# WGTOTW
##Project in PHPMVC

###Instruction for installing WGTOTW - page about League of Legends.

- Files you see outside the folders you should put to the root folder.
- Clone repository or Download it as ZIP. 

#####Then you have to do some changes in some files: 
1. .htaccess :
    * #Rewrite module must be on for clean urls to work. Change base url in #Rewrite base if necessary.
2. app/config/config_mysql.php :
    * Change the setting for the database. This file is referres to from index.php
3. css/anax-grid :
    * This folder must be writable (chmod 777) for style.php and .less styles to work.
4. Then you have to setup database. With this I mean that u create tables in your database and fill with some example content.
    * For this in your web browser, type in : "[YOUR_INSTALLATION_PATH]/webroot/setup". If you are on the main page its enough to change /index.php to /setup. 

