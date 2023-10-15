{
  description = "A flake for PHP 8.0 Magento development";

  inputs.nixpkgs-unstable.url = "github:NixOS/nixpkgs/nixpkgs-unstable";

  outputs = { self, nixpkgs-unstable }: {
    devShell.x86_64-linux = with nixpkgs-unstable.legacyPackages.x86_64-linux; mkShell {

      buildInputs = [
        php82
        php82Extensions.gd
        php82Extensions.intl
        php82Extensions.mbstring
        php82Extensions.pdo
        php82Extensions.pdo_mysql
        php82Extensions.soap
        php82Extensions.xml
        php82Extensions.zip
        php82Extensions.bcmath
        php82Extensions.sockets
        php82Extensions.xsl
        php82Extensions.opcache
        php82Packages.composer

        git
        mysql80
#        elasticsearch
        nginx
        neovim
        eza
        starship
        zsh
      ];

      shellHook = ''
        # Permanent directory for our session
        ENV_DIR=~/.dev_environment
        mkdir -p $ENV_DIR

        # Use .zshrc in our environment directory
        echo 'eval "$(starship init zsh)"' > $ENV_DIR/.zshrc

        # Source .env file if it exists in the current directory
        if [ -f .env ]; then
          source .env
        fi

        # Aliases
        echo 'alias ls="exa --color always"' >> $ENV_DIR/.zshrc

        # Start zsh with our environment's .zshrc
        ZDOTDIR=$ENV_DIR exec zsh
      '';
    };
  };
}
