Runroom - Archetype Drupal
==========================

![ci](https://github.com/Runroom/archetype-drupal/workflows/ci/badge.svg)
![qa](https://github.com/Runroom/archetype-drupal/workflows/qa/badge.svg)

## Requirements

To run this project, you need to have:

- [Git](https://git-scm.com/)
- [Nvm](https://github.com/nvm-sh/nvm)
- [Yarn](https://yarnpkg.com/)
- [Mkcert](https://github.com/FiloSottile/mkcert)

Docker (new method):

- [Docker](https://www.docker.com/)

Vagrant (old method):

- [VirtualBox](https://www.virtualbox.org/)
- [Vagrant](https://www.vagrantup.com/)
- [Vagrant Host Manager](https://github.com/devopsgroup-io/vagrant-hostmanager)

## Setup

Docker (new method):

    cd docker
    make up
    make provision

Vagrant (old method):

    vagrant up

To generate build assets:

    nvm use
    yarn
    yarn encore dev

## Docker (new method)

Open `https://localhost:8443` in your browser

## Vagrant (old method)

Open `https://drupal.local` in your browser.

## Contribute

Please refer to [CONTRIBUTING](doc/Contributing.md) for information on how
to contribute to the Archetype and its related projects.

## Additional documentation

- [Ansible](doc/Ansible.md)
- [Code of conduct](doc/Code_of_conduct.md)
- [Continuous Integration](doc/Continuous_integration.md)
- [Contributing](doc/Contributing.md)
- [Deployment](doc/Deployment.md)
- [Docker](doc/Docker.md)
- [Drupal](doc/Drupal.md)
- [MailHog](doc/MailHog.md)
