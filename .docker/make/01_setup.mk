NODE_MODULES_DIR = node_modules
CERTS_DIR = .docker/traefik/certs
BUILD_DIR = public/build

setup:
	mkdir --parents $(NODE_MODULES_DIR)
.PHONY: setup

certs: 
	mkcert -install
	mkdir --parents $(CERTS_DIR)
	mkcert -cert-file $(CERTS_DIR)/cert.crt -key-file $(CERTS_DIR)/cert.key localhost
.PHONY: certs