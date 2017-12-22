PhpDaemon 
==============


Run php daemonized


Installation
-------------

### for most recent release
add the following to composer.json
```json
"require": {
    "tradeface/phpdaemon":"master"
}
```

```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/tradeface/phpdaemon"
    }
],
```

Example
---
Please check out the example on setup details

Running the example daemon
---
```bash
$ cd vendor/tradeface/phpdaemon/example
$ ./main.php start
...
...
...
$ ./main.php stop
```
