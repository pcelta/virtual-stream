[![Build Status](https://img.shields.io/travis/pcelta/virtual-stream/master.svg?style=flat-square)](https://travis-ci.org/pcelta/virtual-stream)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/pcelta/virtual-stream/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/pcelta/virtual-stream/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/pcelta/virtual-stream/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/pcelta/virtual-stream/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/pcelta/virtual-stream.svg?style=flat-square)](https://packagist.org/packages/pcelta/virtual-stream)
[![Total Downloads](https://img.shields.io/packagist/dt/pcelta/virtual-stream.svg?style=flat-square)](https://packagist.org/packages/pcelta/virtual-stream)
[![License](https://img.shields.io/packagist/l/pcelta/virtual-stream.svg?style=flat-square)](https://packagist.org/packages/pcelta/virtual-stream)

# virtual-stream
The Virtual Stream will help you to avoid saving files into filesystem. This is very useful when we're transferring files and testing. 

## Instalation

```json
{
    "require": {
        "pcelta/virtual-stream": "dev-master"
    }
}
```


## How to use it
This library implements StreamWrapper class definition exactly as shown here: http://php.net/manual/en/class.streamwrapper.php.

Basically, this class implements all methods needed to avoid calls to filesystem. For example, in this scenario below
you can see many function handling a file resource. 

```php

$filename = __DIR__ . '/dummy.txt';
$resource = fopen($filename, 'w+');

fwrite($resource, 'first');
fwrite($resource, 'second,');
$result = fread($resource, 20);

fclose($resource);

```
To make sure this example will work you need to give permission on current directory for writing and reading 
because PHP is going to write it.

That could be avoided using Virtual Stream!

Let's have a look at same example above but right now it is using Virtual Stream.

```php

Stream::register(); // register a new StreamWrapper

$filename = sprintf('%s://dummy.txt', Stream::DEFAULT_PROTOCOL);
$resource = fopen($filename, 'w+');

fwrite($resource, 'first');
fwrite($resource, 'second,');
$result = fread($resource, 20);

fclose($resource);

Stream::unregister(); // remove a StreamWrapper registered.

```

In this example, PHP will not use the filesystem to perform these f* functions.