# Description #
This project is a fork from [link]http://thebestsolution.org/zend-login-with-facebook-twitter-and-google/ 

# Instructions #

### Step 1 ###
* Clone this repository:

~~~
git clone git://github.com/jimmynewtron/MultiAuth
~~~

### Step 2 ###
* Move the MultiAuth folder to your Zend Application library folder


### Step 3 ###
* Facebook API settings

~~~
facebook.client_id      = "xxxx"
facebook.client_secret  = "xxxx"
facebook.redirect_uri   = "http://YOUR_HOSTNAME/AUTH_URL"
facebook.scope          = "email"
~~~

The `client_id` and `client_secret` can be found at https://developers.facebook.com/apps

On the Facebook [site](https://developers.facebook.com/apps) set the Site URL to http://YOUR_HOSTNAME/AUTH_URL

The different scopes can be found at https://developers.facebook.com/docs/reference/api/permissions/

* Google API settings 

~~~
google.client_id        = "xxxx"
google.client_secret    = "xxxx"
google.redirect_uri     = "http://YOUR_HOSTNAME/AUTH_URL"
google.scope            = "https://www.googleapis.com/auth/userinfo.profile"
~~~

The `client_id` and `client_secret` can be found at https://code.google.com/apis/console

On the Google [site](https://code.google.com/apis/console) set the Redirect URI to http://YOUR_HOSTNAME/AUTH_URL

I could not find a list with all the available scopes, you just have to google for it.

* Twitter API settings

~~~
twitter.consumerKey     = "xxxx"
twitter.consumerSecret  = "xxxx"
twitter.callbackUrl     = "http://YOUR_HOSTNAME/AUTH_URL"
~~~

The `consumerKey` and `consumerSecret` can be found at https://dev.twitter.com/apps

On the Twitter [site](https://dev.twitter.com/apps) set the Callback URL to http://YOUR_HOSTNAME/AUTH_URL

## Step 4 ##
* Authenticating:

Inside your Zend controller, get a MultiAuth instance and authenticate:

~~~
$auth = \MultiAuth\Auth::getInstance();
$auth->authenticate(\MultiAuth\Provider::ADAPTER_FACEBOOK);
~~~

Use one of the valid auth constants defined in `MultiAuth\Provider`:
- `Provider::ADAPTER_FACEBOOK`
- `Provider::ADAPTER_GOOGLE`
- `Provider::ADAPTER_TWITTER`
- `Provider::ADAPTER_BASIC`

* Getting a valid auth url:


## More ##

The original project is focused in show how to perform multiple authentications in the same request and interact with multi APIs (e.g. to post in a Facebook wall and tweet).

This fork changed some class to turn it into a library that authenticate within an social network API, or authenticates using form data (e.g. email/password)
