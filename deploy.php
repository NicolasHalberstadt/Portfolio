<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:NicolasHalberstadt/Portfolio.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('147.79.103.103')
    ->set('remote_user', 'u416463632')
    ->set('deploy_path', '/home/u416463632/domains/nicolashalberstadt.com/public_html')
    >set('identity_file', '~/.ssh/id_rsa');

// Hooks

after('deploy:failed', 'deploy:unlock');
