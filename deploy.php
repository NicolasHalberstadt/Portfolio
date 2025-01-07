<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:NicolasHalberstadt/Portfolio.git');
set('http_user', 'u416463632');
set('writable_use_sudo', false);
set('writable_mode', 'chmod');
set('bin/composer', '{{release_path}}/composer.phar');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('147.79.103.103')
    ->set('remote_user', 'u416463632')
    ->set('deploy_path', '/home/u416463632/domains/nicolashalberstadt.com/public_html')
    ->set('identity_file', '~/.ssh/id_rsa')
    ->set('port', '65002');

// Tasks

// Télécharge Composer dans chaque release
task('deploy:composer', function () {
    run('cd {{release_path}} && curl -sS https://getcomposer.org/download/2.4.1/composer.phar -o composer.phar');
    run('chmod +x {{release_path}}/composer.phar'); // Rendre composer.phar exécutable
});

// Ajoute la tâche au cycle de déploiement
before('deploy:vendors', 'deploy:composer');

// Ajoute la commande cache:clear
task('deploy:clear_cache', function () {
    run('cd {{release_path}} && php bin/console cache:clear --env=prod --no-warmup');
});

// Sauter cache:clear si tu veux, sinon l'ajouter avant 'deploy:vendors'
after('deploy:vendors', 'deploy:clear_cache');

// Hooks

after('deploy:failed', 'deploy:unlock');