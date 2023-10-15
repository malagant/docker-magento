UPDATE  core_config_data
SET     value = 'https://skylotec.test/'
WHERE   path LIKE 'web/%secure/base_url'
AND     scope = 'default';

UPDATE  core_config_data
SET     value = 'https://gentic.test/'
WHERE   path LIKE 'web/%secure/base_url'
AND     scope = 'websites'
AND     scope_id = 9;

UPDATE  core_config_data
SET     value = 'db'
WHERE   path = 'wordpress/database/host';

UPDATE  core_config_data
SET     value = 'EWNWg/gIM9w='
WHERE   path = 'wordpress/database/password';

UPDATE  core_config_data
SET     value = 'C92zmdt+vc+1uTWJ7OwqHQ=='
WHERE   path = 'wordpress/database/dbname'
AND     scope = 'default';

UPDATE  core_config_data
SET     value = 'EWNWg/gIM9w='
WHERE   path = 'wordpress/database/username';

UPDATE  core_config_data
SET     value = 'C92zmdt+vc+1uTWJ7OwqHQ=='
WHERE   path = 'wordpress/database/dbname'
AND     scope = 'websites'
AND     scope_id = 9;

UPDATE  core_config_data
SET     value = '/var/www/wp-gentic'
WHERE   path = 'wordpress/integration/path'
AND     scope = 'websites'
AND     scope_id = 9;

UPDATE  core_config_data
SET     value = '/var/www/html/htdocs/wp'
WHERE   path = 'wordpress/integration/path'
AND     scope = 'default';
