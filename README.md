# Kolyya Provider for OAuth 2.0 Client

This package provides Kolyya OAuth 2.0 support for the PHP
League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require kolyya/oauth2-kolyya
```

## Development

At the root of the symfony project, create a directory `Kolyya`.

Clone this repository to this directory:

```shell
git clone git@github.com:kolyya/oauth2-kolyya.git
```

Add to file `composer.json`:

```json5
{
  //...
  "autoload": {
    "psr-4": {
      //...
      "Kolyya\\OAuth2Client\\": "Kolyya\\oauth2-kolyya\\src"
    }
  },
}
```

And execute the command:

```shell
composer dump-autoload
```
