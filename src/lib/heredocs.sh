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

alternatives_template() {
    /usr/bin/cat << EOF
manual
%s/.local/bin/php

EOF
}
