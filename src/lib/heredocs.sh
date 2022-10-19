config_template() {
    /usr/bin/cat << EOF
; Machine-specific PHP settings.
; priority=99
;
; Add any PHP settings you wish to set, override, etc. to this file. By default,
; 'phps config' will run 'phpenmod' to ensure this configuration is used; the
; priority above will be used, ensuring it overrides any other settings defined
; elsewhere.
;
; If you are unsure what settings are available, look in ../{SAPI}/php.ini.
EOF
}

alternatives_template_php() {
    /usr/bin/cat << EOF
manual
%s/.local/bin/php

EOF
}

alternatives_template_php_config() {
    /usr/bin/cat << EOF
manual
%s/.local/bin/php-config

EOF
}

alternatives_template_phpize() {
    /usr/bin/cat << EOF
manual
%s/.local/bin/phpize

EOF
}

alternatives_template_phar() {
    /usr/bin/cat << EOF
manual
%s/.local/bin/phar

EOF
}

extension_config_template() {
    /usr/bin/cat << EOF
; configuration for php %s module
; priority=20
extension=%s.so
EOF
}
