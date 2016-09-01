# codeigniter-google-oauth
codeigniter google Oauth Api

#Use Composer to make effective application. 

1. First add Google php SDK to your CodeIgniter application.
	- To do this open `composer.json` file and add `"google/apiclient": "1.0.*@beta"` package in `"require"`
2. After add package in json file open console and run `composer update`

#Add your Google credential setting in `application/config/config.php`

1. `$config['GOOGLE_APP_NAME'] = 'PHP Google OAuth Login Example';`
2. `$config['GOOGLE_ID'] = ''`
3. `$config['GOOGLE_SECRET'] = 'GOOGLE_SECRET';`
4. `$config['GOOGLE_CALLBACK_URL'] = 'googleController/callBack';`