UPDATE  wp_options
SET     option_value = 'https://skylotec.test/'
WHERE   option_name = 'home';

UPDATE  wp_options
SET     option_value = 'https://skylotec.test/wp/'
WHERE   option_name = 'siteurl';
