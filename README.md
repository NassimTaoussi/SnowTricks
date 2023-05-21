# PROJET SYMFONY SNOWTRICKS

###### Création d'un site communautaire de partage de figures sur la thématique du snowboard réaliser avec le framework Symfony.

## INSTALLATION


1. Clonez ou téléchargez le repository GitHub dans le dossier voulu :
``
git clone https://gitlab.com/NassimTaoussi/SnowTricks.git
``

2. installez les dépendances du projet avec Composer :
``
symfony composer install
``

3. Puis nstallez les dépendances du projet coté front-end avec :
``
npm install
``

4. Créez la base de données si elle n'a pas déjà été créer :
``
Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
``

5. Configurez vos variables d'environnement dans le fichier .env.local :
``
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
MAILER_DSN=smtp://localhost:1025
``

6. Pour crée les différentes tables dans la base 
``
php bin/console doctrine:schema:update --force
``

7. On peut ensuite lancer le serveur du projet avec :
``
symfony server:start -d
``

8. Pour compiler l'ensemble des assets :
``
npm run watch
``