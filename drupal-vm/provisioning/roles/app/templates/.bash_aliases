if [ -f /vagrant/.vault_pass ]; then
    export ANSIBLE_VAULT_PASSWORD_FILE=/vagrant/.vault_pass
fi

ansible-run () {
    ANSIBLE_CONFIG=/vagrant/drupal-vm/provisioning/ansible.cfg ansible-playbook /vagrant/drupal-vm/provisioning/playbook.yml -i "localhost," -c local --extra-vars "config_dir=/vagrant/drupal-vm drupalvm_env=vagrant" --skip-tags="php,database,drupal" --tags "$1";
}
