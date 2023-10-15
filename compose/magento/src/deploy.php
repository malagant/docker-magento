<?php
use function Deployer\set;
use function Deployer\task;
use function Deployer\host;
use function Deployer\after;

$recipesDir = './vendor/conlabz/deployer-recipes';

require_once $recipesDir . '/recipe/artifact.php';
require_once $recipesDir . '/recipe/cachetool.php';
require_once $recipesDir . '/recipe/mage.php';
require_once $recipesDir . '/recipe/misc.php';

set('keep_releases', 2);
set('shared_dirs', ['htdocs/var', 'htdocs/media', 'htdocs/sitemaps', 'htdocs/skylofiles', 'htdocs/wp']);
set('shared_files', ['htdocs/app/etc/local.xml', "htdocs/app/etc/db.php", "htdocs/.htaccess", "htdocs/robots.txt", 'htdocs/cron.sh']);
set('writable_dirs', ['htdocs/var', 'htdocs/media', 'htdocs/sitemaps', 'htdocs/skylofiles', 'htdocs/wp']);
set('artifact_file', 'release.tar.gz');

set('http_user', 'p207831');
set('writable_mode', 'chmod');
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('bin/php', 'php');
set('bin/composer', 'composer');
set('magerun', 'php_cli shell/n98-magerun.phar');
set('mage_dir', 'htdocs');
set('cachetool_binary', 'cachetool-7.1.0.phar');
set('cc_script', 'htdocs/shell/cleancache.php');
set('clear_paths', [
    'release.tar.gz',
]);

###########################
########## Server #########
###########################

# DEV
host('dev')
    ->hostname('c-1.maxcluster.net')
    ->user('web-user')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->set('deploy_path', '/var/www/share/skylotec.shopdev.on-conlabz.de/magento')
    ->set('http_user', 'web-user')
    ->stage('dev');

# STAGING
host('staging')
    ->hostname('host.skyshop.business-hub-01.skylotec.com')
    ->user('p207831')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->set('deploy_path', '~/html/staging/magento')
    ->stage('staging');

# PRODUCTION
host('production')
    ->hostname('host.skyshop.business-hub-01.skylotec.com')
    ->user('p207831')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->set('deploy_path', '~/html/magento')
    ->stage('production');

###########################
########## Tasks ##########
###########################

task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'artifact:upload',
    'deploy:writable',
    'artifact:extract',
    'deploy:shared',
    'deploy:symlink',
    'cachetool:clear:opcache',
    'mage:cc',
    'deploy:unlock',
    'deploy:clear_paths',
    'cleanup'
])->desc('Deploy');

after('deploy', 'success');
after('deploy:failed', 'deploy:unlock');
