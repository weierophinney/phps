# shellcheck disable=SC2215,SC2288,SC2168,SC2154
name: disable
help: |-
  Remove or disable a PHP version.

  Removes a PHP version from the list of alternatives, which will prevent it
  from being displayed by 'version list', as well as prevent the ability to
  use/switch to the version, or enable/disable extensions for it.

  Typically, only use this after uninstalling the version from your computer.

  When providing a version, provide it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"

if ! version_disable "${version}"; then
    exit 1
fi
