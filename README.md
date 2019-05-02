# phps

This is a tool to allow me to easily switch between PHP versions installed via
the ondrej/php PPA, including:

- List available versions
- Switch versions
- Temporarily use a version
- Configure the current version
- Compile, install, and enable extensions for the current version.

See [my blog post on using the ondrej/php PPA](https://mwop.net/blog/2019-04-30-ondrej-multiversion-php.html)
for background on why I created this tool.

## Installation

Clone the repository:

```bash
$ git clone https://github.com/weierophinney/phps.git
```

Then enter it and install dependencies:

```php
$ cd phps
$ composer install
```

Then create a script in `$HOME/.local/bin/` (or elsewhere on your `$PATH`) with
the following contents:

```bash
#!/bin/sh
# Use the shell of your choice on the above line.
# Use the PHP version of your choice on the line below.
/usr/bin/php7.2 path/to/phps/bin/phps.php $@
```

I suggest calling the script `phps`.

Make the script executable:

```bash
$ chmod 755 phps
```

## Usage

```bash
# Get usage information:
$ phps
```
