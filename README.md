# weatherAlerter
A PHP script that alerts by mail when the weather conditions are met in the next 5 days, using OpenWeather.

# Installation
Copy config.php.default to config.php and configure the parameters.
You'll need a valid API Key from https://openweathermap.org/api

Execute the script invoking index.php and passing the parameter pwd=[the password you configured in config.php]. For example: index.php?pwd=abc1234.

If you want the mail to be sended, pass the parameter mail=1 as well. For example, index.php?pwd=abc1234.&mail=1
