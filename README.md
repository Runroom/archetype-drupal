# Archetype Drupal

[![CI](https://github.com/Runroom/archetype-drupal/actions/workflows/ci.yaml/badge.svg)](https://github.com/Runroom/archetype-drupal/actions/workflows/ci.yaml)
[![QA](https://github.com/Runroom/archetype-drupal/actions/workflows/qa.yaml/badge.svg)](https://github.com/Runroom/archetype-drupal/actions/workflows/qa.yaml)
[![Build](https://github.com/Runroom/archetype-drupal/actions/workflows/build.yaml/badge.svg)](https://github.com/Runroom/archetype-drupal/actions/workflows/build.yaml)

## Requirements

To run this project, you need to have:

- [Git](https://git-scm.com/)
- [Nvm](https://github.com/nvm-sh/nvm)
- [NPM](https://www.npmjs.com/)
- [Mkcert](https://github.com/FiloSottile/mkcert)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/cli-command/)

## Setup

To start the project for the first time:

```bash
    make
```

To generate build assets:

```bash
    nvm use
    npm clean-install
    npx encore dev
```

- Open `https://localhost:8443` in your browser.
- Open `https://localhost:8425` in your browser to access Mailpit.

To log in to the user panel for the first time:

```bash
    make ssh

    drush user:login --uri=https://localhost:8443
```

To use xDebug, after the initial `make`, you can run:

```bash
    make up-debug
```

And you will only restart the `app` container with the xDebug enabled, to disable it again, run
`make up` again.

To run the application in production mode:

```bash
    make prod
```

Remember to run `make dev` when you finish working on the project in prod mode.

## Contribute

Please refer to [CONTRIBUTING](doc/Contributing.md) for information on how to contribute to the
Archetype and its related projects.

## Additional documentation

- [Code of conduct](doc/Code_of_conduct.md)
- [Continuous Integration](doc/Continuous_integration.md)
- [Contributing](doc/Contributing.md)
- [Deployment](doc/Deployment.md)
- [Docker](doc/Docker.md)
- [Drupal](doc/Drupal.md)
- [Mailpit](doc/Mailpit.md)
