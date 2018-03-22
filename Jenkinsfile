#!groovy

PROJECT_NAME = env.JOB_NAME.replace('/' + env.JOB_BASE_NAME, '')
SLACK_ERROR_MESSAGE = "${PROJECT_NAME} - ${env.BUILD_DISPLAY_NAME} Failed (<${env.BUILD_URL + 'console'}|Open>)\n${env.BRANCH_NAME}"
PHP_VERSION = '7.1'

pipeline {
    agent any

    options { buildDiscarder(logRotator(numToKeepStr: '10')) }

    stages {
        stage('Build') {
            steps {
                sh "if [ ! -f composer.phar ]; then curl -sS https://getcomposer.org/installer | php${PHP_VERSION}; fi"
                sh "php${PHP_VERSION} composer.phar self-update"
                withEnv(['SYMFONY_ENV=test']) {
                    sh "php${PHP_VERSION} composer.phar install -an --prefer-dist --no-progress --apcu-autoloader"
                }
            }
        }
        stage('Deploy') {
            when { expression { return env.BRANCH_NAME in ['development', 'master'] } }
            steps {
                build job: "${PROJECT_NAME}_deploy", parameters: [
                    [$class: 'StringParameterValue', name: 'BRANCH', value: env.BRANCH_NAME]
                ], wait: false
            }
        }
    }

    post {
        failure { slackSend(color: 'danger', message: SLACK_ERROR_MESSAGE) }
    }
}
