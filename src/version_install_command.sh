# shellcheck disable=SC2215,SC2168
name: install
help: |-
  Install a new PHP version.

  Installs and enables a new PHP version, with any additional packages as
  provided in the 'extension' arguments. The 'common', 'cli', and 'dev'
  packages for the given version are always installed.

  When providing a version, provide in in major.minor format.
args:
- import: src/flags/version_arg.yml
- name: extension
  help: "Which additional extensions do you wish to install? (Provide package names minus 'php{version}-' prefix)"
  required: false
  repeatable: true
---
local version="${args[version]}"
local packages=()
local extension
local extensions

if [[ "${args[extension]+abc}" ]]; then
    eval "extensions=(${args[extension]})"
else
    extensions=()
fi

extensions=("${extensions[@]}" "cli" "common" "dev")

green "Installing PHP version ${version}"

for extension in "${extensions[@]}"; do
    packages=("${packages[@]}" "php${version}-${extension}")
done

if ! sudo apt -y install "${packages[@]}"; then
    red "Error installing PHP ${version} (with extensions ${extensions[*]}); check logs for details"
    exit 1
elif ! version_enable "${version}"; then
    exit 1
fi
