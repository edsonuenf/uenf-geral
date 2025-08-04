#!/usr/bin/env bash

# Exit on error
set -e

# Default values
DB_NAME=${1-wp_test}
DB_USER=${2-root}
DB_PASS=${3-}
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}
WP_TESTS_DIR=${6-/tmp/wordpress-tests-lib}
WP_CORE_DIR=${7-/tmp/wordpress}

# Create directories
mkdir -p $WP_TESTS_DIR
mkdir -p $WP_CORE_DIR

# Download WordPress
if [ ! -d $WP_CORE_DIR ]; then
    echo "Downloading WordPress..."
    wp core download --path=$WP_CORE_DIR --version=$WP_VERSION --allow-root
fi

# Create test database if it doesn't exist
mysql -u $DB_USER -p$DB_PASS -h $DB_HOST -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

# Install test suite if not already installed
if [ ! -f $WP_TESTS_DIR/includes/functions.php ]; then
    echo "Installing test suite..."
    svn co --quiet https://develop.svn.wordpress.org/trunk/tests/phpunit/includes/ $WP_TESTS_DIR/includes
    svn co --quiet https://develop.svn.wordpress.org/trunk/tests/phpunit/data/ $WP_TESTS_DIR/data
fi

# Create wp-tests-config.php
if [ ! -f $WP_TESTS_DIR/wp-tests-config.php ]; then
    echo "Creating test configuration..."
    cp $WP_TESTS_DIR/includes/wp-tests-config-sample.php $WP_TESTS_DIR/wp-tests-config.php
    
    # Update configuration
    sed -i "s:dirname( __FILE__ ) . '/src/':'$WP_CORE_DIR/':" $WP_TESTS_DIR/wp-tests-config.php
    sed -i "s/youremptytestdbnamehere/$DB_NAME/" $WP_TESTS_DIR/wp-tests-config.php
    sed -i "s/yourusernamehere/$DB_USER/" $WP_TESTS_DIR/wp-tests-config.php
    sed -i "s/yourpasswordhere/$DB_PASS/" $WP_TESTS_DIR/wp-tests-config.php
    sed -i "s|localhost|${DB_HOST}|" $WP_TESTS_DIR/wp-tests-config.php
    
    # Add test configuration
    echo "
// Test configuration
define( 'WP_DEBUG', true );
define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );
" >> $WP_TESTS_DIR/wp-tests-config.php
fi

echo "WordPress test environment configured successfully!"
