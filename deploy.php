<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:NicolasHalberstadt/Portfolio.git');
set('default_environment', ['LC_MESSAGES' => 'C']);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('Portfolio')
    ->set('remote_user', 'u416463632')
    ->set('deploy_path', '/home/u416463632/domains/nicolashalberstadt.com/public_html')
    ->set('identity_file', '~/.ssh/id_rsa');

// Tasks

task('deploy:assets', function () {
    // Navigue vers le répertoire du projet et compile les assets
    run('cd {{release_path}} && php bin/console sass:build');
    run('cd {{release_path}} && php bin/console asset-map:compile');
});

// Hooks

after('deploy:symlink', 'deploy:assets');
after('deploy:failed', 'deploy:unlock');
