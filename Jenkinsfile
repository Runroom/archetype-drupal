#!groovy

PROJECT_NAME = env.JOB_NAME.replace('/' + env.JOB_BASE_NAME, '')
SLACK_ERROR_MESSAGE = "${PROJECT_NAME} - ${env.BUILD_DISPLAY_NAME} Failed (<${env.BUILD_URL + 'console'}|Open>)\n${env.BRANCH_NAME}"
COMPOSER = '/usr/local/bin/composer'
PHP_VERSION = '7.1'

pipeline {
    agent any

    options { buildDiscarder(logRotator(numToKeepStr: '10')) }

    stages {
        stage('Build') {
            steps {
                sh "php${PHP_VERSION} ${COMPOSER} self-update"
                withEnv(['SYMFONY_ENV=test']) {
                    sh "php${PHP_VERSION} ${COMPOSER} install --prefer-dist --no-progress --no-interaction --classmap-authoritative"
                }
            }
        }
        stage('Deploy') {
            when { expression { return env.BRANCH_NAME in ['development'] } }
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
