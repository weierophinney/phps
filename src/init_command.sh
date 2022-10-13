# shellcheck disable=SC2215,SC2168
name: init
help: |-
  Prepare your environment to manage different PHP versions.

  It performs the following tasks:

  - Initializes the packages.sury.org repository, if it is not already.
  - Creates files that allow the user update-alternatives settings to know which PHP binaries it should manage.
---
local alternativesDir="${HOME}/.local/var/lib/alternatives"
local alternativesFile="${alternativesDir}/php"
local prompt

if [[ -f "${alternativesFile}" ]]; then
    green "Your environment is already prepared!"
else
    blue "Do you want to create the file ${alternativesFile} to handle your PHP versions?"
    read -r prompt
    if [[ "${prompt}" =~ ^[y|Y] ]]; then
        if [[ ! -d "${alternativesDir}" ]]; then
            mkdir -p "${alternativesDir}"
        fi

        # shellcheck disable=SC2059
        printf "$(alternatives_template)" "${HOME}" > "${alternativesFile}"

        green "Alternatives file created"
    else
        red "Aborting"
        exit 1
    fi
fi
