# shellcheck disable=SC2215,SC2168
name: list
help: |-
  List supported PHP versions.

  Provides a list of installed PHP versions to choose from.
---
local versions
local version

mapfile -t versions < <(alternatives_get_supported_versions | sort)
for version in "${versions[@]}"; do
    echo "- ${version}"
done
