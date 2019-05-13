alias composer-install="{{ ansible_env.HOME }}/bin/composer install -o --working-dir=\"/vagrant\""

if [ -f /vagrant/.vault_pass ]; then
    export ANSIBLE_VAULT_PASSWORD_FILE=/vagrant/.vault_pass
fi

ansible-run () {
    ansible-playbook /vagrant/ansible/playbook.yaml -i "localhost," -c local --tags "$1";
}
