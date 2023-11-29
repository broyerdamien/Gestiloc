#!/bin/bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
. $HOME/.nvm/nvm.sh
nvm install node
npm install --global yarn
