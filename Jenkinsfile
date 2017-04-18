#! groovy

node('swarm') {
    stage('Test') {
        def dockerenv = docker.image('ubuntu:14.04')
        dockerenv.inside {
            checkout scm
            sh 'apt-get update && apt-get install -y curl php5'
            sh 'php -v'
            sh 'curl -s http://getcomposer.org/installer | php'
            sh 'php composer.phar install'
            sh 'php composer.phar install --prefer-source --no-interaction'
            //sh 'mkdir -p circle_test_reports/phpunit'
            //sh 'timeout 1200 ./vendor/bin/phpunit --log-junit circle_test_reports/phpunit/junit.xml'
            sh './vendor/bin/phpunit'
        }
    }
}

