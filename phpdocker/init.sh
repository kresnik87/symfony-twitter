#!/usr/bin/env bash

#!/bin/bash
clear;
echo "Creating Symfony project with base Configuration in Docker";
echo "Docker Config";
echo "Enter Project Name:";
read projectName;
echo "Enter root pass for container mysql";
read rootPass;
echo "Enter db name for container mysql";
read dbName;
echo "Enter db user for container mysql";
read dbUser;
echo "Enter db user pass for container mysql";
read dbUserPass;
cp -v ./phpdocker/.env_file ./phpdocker/.env_file.local;
rm ./phpdocker/.env_file.local.back;
sed -i.back 's\'MYSQL_ROOT_PASSWORD='\'MYSQL_ROOT_PASSWORD=${rootPass}'\' ./phpdocker/.env_file.local;
sed -i.back 's\'MYSQL_DATABASE='\'MYSQL_DATABASE=${dbName}'\' ./phpdocker/.env_file.local;
sed -i.back 's\'MYSQL_USER='\'MYSQL_USER=${dbUser}'\' ./phpdocker/.env_file.local;
sed -i.back 's\'MYSQL_PASSWORD='\'MYSQL_PASSWORD=${dbUserPass}'\' ./phpdocker/.env_file.local
docker-compose up -d;
echo "Now copy MYSQL container id";
docker ps;
echo "Paste  MYSQL Container id:";
read containerId;
containerIp= docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' ${containerId} '\n';
read -p "Copy and paste container ip:" containerIp;
echo "Your mysql container ip is ${containerIp}";

git remote set-url origin $2
cp -v .env .env.local;
sed -i.bak 's\'DATABASE_URL=^'\'DATABASE_URL=mysql://${dbUser}:${dbUserPass}@${containerIp}:3306/${dbName}'\' .env.local
composer install;


docker-compose exec php-fpm bin/console doctrine:schema:update -f;
docker-compose exec php-fpm bin/console fos:oauth-server:create-client --grant-type="password"
echo "Copy Client ID";
read client_id;
echo "Copy Client Secrect";
read client_secret;
sed -i.bak 's\'CLIENT_SECRET=^'\'CLIENT_SECRET=${client_secret}'\' .env.local;
sed -i.bak 's\'BASE_URL=^'\'BASE_URL=http://localhost:8080'\' .env.local;
read -p "Enter the mailer url [no-reply@${1}.com]" mailer_url;
sed -i.bak 's\'MAILER_URL=^'\'MAILER_URL=${mailer_url:-no-reply@${1}.com}'\' .env.local;
sed -i.bak 's\'APP_NAME=^'\'APP_NAME=${projectName:-${1}}'\' .env.local;

docker-compose exec php-fpm bin/console fos:user:create  --super-admin admin admin@${projectName}.local admin-${projectName}

docker-compose exec php-fpm bin/console assets:install --symlink
rm .env.local.bak;
cp -v ./init.sh ./phpdocker/init.sh
rm init.sh;
rm db.sh;
docker-compose exec php-fpm bin/console cache:clear;
echo "Now you can edit the file .env.local to edit variables";
echo "The backend user is admin with as admin-${projectName} password";
echo "Create branch develop and first commit:";
read answer;

if [${answer} =='y']
then
git checkout -b develop
git add *
git add .env
git add .gitignore
git commit -am $1" first checkin"
git push -u origin develop
chmod 777 var/* -R;
else
 echo "Config Finish";
fi

