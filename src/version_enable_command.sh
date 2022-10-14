# shellcheck disable=SC2215,SC2154,SC2168
name: enable
help: |-
  Enable a newly installed PHP version.

  Enables a previously installed PHP version, exposing it to allow switching to
  and from the new version.

  When providing a version, provide it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"

if ! version_enable "${version}"; then
    exit 1
fi
