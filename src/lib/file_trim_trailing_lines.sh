#########################################
## Trim trailing lines from end of file
##
## Arguments:
##   $1: file to trim
#########################################
file_trim_trailing_lines() {
    local file=$1
    local end
    local trailing

    trailing=$(tac "$file" | sed -n '/^[ \t]*$/!q; p' | wc -c)
    end=$(( $(wc -c < "$file") - trailing ))
    dd status=none bs=1 seek=$end count=0 of="$file"
}
