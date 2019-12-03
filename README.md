# phps

This is a tool to allow me to easily switch between PHP versions installed via
the ondrej/php PPA, including:

- List available versions
- Switch versions
- Temporarily use a version
- Configure the current version
- Compile, install, and enable extensions for the current version.
- Enable previously installed extensions for the current PHP version.
- Disable previously installed extensions for the current PHP version.

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

Use the `init` command to prepare your environment:

```bash
$ phps init
```

Install a new PHP version, with the curl and bcmath extensions:

```bash
$ phps version:install 7.4 curl bcmath
```

Uninstall a PHP version entirely:

```bash
$ phps version:uninstall 5.6
```

Manually tell update-alternatives about PHP versions you wish to manage (e.g.,
if you were already using the sury.org repository to handle multiple PHP
versions):

```bash
$ phps version:enable 7.3
```

If you uninstall a PHP version, you can tell update-alternatives you're no
longer insterested in it:

```bash
$ phps version:disable 7.3
```

See what versions are currently being managed:

```bash
$ phps version:list
```

Switch which PHP version is used by default:

```bash
$ phps switch 7.4
```

Temporarily use another PHP version:

```bash
$ phps use 5.6
```

> The above will print out an alias you can define. Aliases only exist for the
> lifetime of the current shell, so opening a new shell will revert to the
> default.
>
> You can remove the alias using the `unalias php` command.

Install an extension for the current PHP version:

```bash
$ phps ext:install swoole
```

Enable an extension in the current PHP version:

```bash
$ phps ext:enable swoole
```

Disable an extension in the current PHP version:

```bash
$ phps ext:disable swoole
```

## Configuration

The command `phps config` allows you to configure the current PHP version. It
does so by doing the following:

- It creates a file named `/etc/php/{VERSION}/mods-available/phps.ini`.
- It runs `sudo phpenmod -v {VERSION} -s cli phps`.
- It opens that file in `$EDITOR`.

The first time it runs, it will perform the first two steps, and you will see
extra output when you do.

If you wish to use that configuration in another SAPI (e.g., php-fpm), run the
`phpenmod` command using that SAPI; e.g., `sudo phpenmod -v {VERSION} -s fpm
phps`.
