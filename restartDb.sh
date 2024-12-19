rm migrations/Version*;
php bin/console doctrine:database:drop --force;
php bin/console doctrine:database:create;
php bin/console doctrine:migrations:diff -n;
php bin/console doctrine:migrations:migrate -n;
php bin/console doctrine:fixtures:load -n;