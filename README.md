# hira-kata-learner

Small project to check how FrankenPHP works.  
Visit [learner.hexdev.me](https://learner.hexdev.me) to check it out.

## Running

Make sure php 8.4 installed.

```bash
# Install FrankenPHP
curl https://frankenphp.dev/install.sh | sh
mv frankenphp /usr/local/bin/

# Install dependencies
composer install

# Run server (Change path if needed)
frankenphp php-server --worker ~/hira-kata-learner/index.php 
```
