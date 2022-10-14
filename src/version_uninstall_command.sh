# shellcheck disable=SC2215,SC2168,SC2154
name: uninstall
help: |-
  Uninstall a PHP version.

  Uninstalls and disables a PHP version, along with all packages installed for
  that PHP version.

  When providing a version, provided it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"
local packages

green "Uninstalling PHP version ${version}"

mapfile -t packages < <(dpkg -l | grep ^ii | awk '{print $2}' | grep "php${version}")
if [[ "${packages[*]+abc}" == "" ]]; then
    green "Nothing to uninstall!"
elif ! sudo apt -y remove "${packages[@]}"; then
    red "Error uninstalling PHP ${version}; check logs for details"
    exit 1
elif ! version_disable "${version}"; then
    exit 1
fi
