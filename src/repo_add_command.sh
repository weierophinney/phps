# shellcheck disable=SC2215,SC2288,SC1010
name: add
help: |-
  Add the packages.sury.org repository to your apt sources list.

  It first checks to see if a list entry already exists. If so, it aborts early
  with a success status.

  Otherwise, it will add the packages.sury.org repo via a new apt sources.list.d
  file, and then run apt-get update.
---
if grep -qrP '(packages\.sury\.org|ppa\.launchpadcontent\.net\/ondrej\/php)' /etc/apt/sources.list.d; then
    green "Repo is already present on your system"
else
    green "Installing Sury repository using add-apt-repository"
    if add-apt-repository ppa:ondrej/php; then
        green "Repo injected and ready!"
    else
        red "Unable to inject Sury PPA; see above logs for details"
        exit 1
    fi
fi
