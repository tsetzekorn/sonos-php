# sonos-php
Using Sonos Control API via PHP

This PHP class set was created to access the Sonos Control API via PHP. The documentation of the API is available 
at https://developer.sonos.com.

To be able to use the API you have to register at the Sonos developer portal and create an "Integration". With this 
you will get a client ID and a client secret. Additionally the user has to grant access to his Sonos system with a 
call to the Sonos Login Service:

https://api.sonos.com/login/v3/oauth?client_id=YourAPIKeyGoesHEre&response_type=code&state=testState&scope=playback-control-all&redirect_uri=https%3A%2F%2Facme.example.com%2Flogin%2Ftestclient%2Fauthorized.html

The callback Uri will receive the auth code of that user. With these three keys - client id, client secret and user's
auth code - entered in the config.ini (see config.ini.sample as reference), the API requests these PHP classes will work.
