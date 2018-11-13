Vagrant.require_version '>= 1.8.5'

Vagrant.configure('2') do |config|
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.manage_guest = true

    config.vm.provider :virtualbox do |v|
        v.customize [
            'modifyvm', :id,
            '--name', 'drupal-vm',
            '--cpus', 1,
            '--memory', 2048,
            '--natdnshostresolver1', 'on',
            '--nictype1', 'virtio',
            '--nictype2', 'virtio',
        ]
    end

    config.vm.define 'drupal-vm' do |node|
        node.vm.box = 'ubuntu/xenial64'
        node.vm.network :private_network, ip: '192.168.88.66', nic_type: 'virtio'
        node.vm.hostname = 'drupal.local'
        node.hostmanager.aliases = ['pimpmylog.drupal.local', 'adminer.drupal.local']

        node.vm.synced_folder './', '/vagrant', type: 'nfs', nfs_udp: false, mount_options: ['actimeo=1']
        node.ssh.forward_agent = true
    end

    config.vm.provision 'ansible_local' do |ansible|
        ansible.compatibility_mode = "2.0"
        ansible.playbook = 'ansible/provisioning/playbook.yml'
        ansible.extra_vars = {
            config_dir: '/vagrant/ansible',
            drupalvm_env: 'vagrant'
        }
        ansible.galaxy_command = 'ansible-galaxy install --role-file=%{role_file} --roles-path=%{roles_path}'
        ansible.galaxy_role_file = 'ansible/provisioning/requirements.yml'
        ansible.galaxy_roles_path = 'ansible/provisioning/requirements'
    end
end
