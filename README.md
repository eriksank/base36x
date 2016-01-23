# base36x

Implements `base36` by reusing the `base64` encoding functions. You can find the original suggestion for this solution strategy [in this forum post](http://forums.phpfreaks.com/topic/218241-php-base36-encodingdecoding-for-textdata/).

## Base36

Base36 is an encoding in which we only use letters `a to z` and digits `0 to 9` to represent data.

It is quite similar to base64, where we use lowercase letters `a to z`, uppercase `A to Z` and digits `0 to 9` to represent the first 62 values, `+` and `/` to represent the two last values and `=` to correct the final translation block.

## What is the problem?

In fact, PHP supports converting to and from base36. However, only from base10:

	$ php -r "echo base_convert('555555555',10,36);"
	96rher

	$ php -r "echo base_convert('96rher',36,10);"
	555555555

Strings are not base10. They are pretty much `base255`. So, what we would need is:

	$ php -r "echo base_convert('555555555',255,36);"

	$ php -r "echo base_convert('96rher',36,255);"

The standard PHP library does not implement this functionality. In fact, it would be quite possible to implement it based on existing functions, but the problem is that the standard algorithm needs support for arbitrary precision numbers (bigint) in order to do that. Therefore, it would require installing the bcmath or gmp php extensions. 

## Why use base36?

For pretty much the same reasons as base64. For example, email systematically uses base64 to embed images and other binary data inside messages. Encoding the data in base64 avoids the problem that the data contains control charactes that will disturb the email protocol.

The problem with base64 is that it distinguishes between lower -and upper case letters. There are numerous situations in which the outer protocol is case insensitive, for example in the case of urls. In that case, base36 will be more suitable. You can use it when you would use base64 but where you do not want to distinguish between lower -and uppercase characters.

By the way, another encoding, `base58`, does basically the same as base64 without using character couples that are considered ambiguous: For example, there are many fonts in which O (letter o) versus 0 (digit zero) are considered ambiguous. It is easy to confuse them by looking at them. So, base58 removes those letters from the encoding base.

## The workaround

In fact, as the suggestion in the forum said, we can repurpose the base64 encoding/decoding functions to produce base36 output. Now, the result is not a real base36 encoding, because not all combinations of valid base36 characters result in a valid base36 string. From there, the name `base36x` instead of base36.

Note: But then again, this should not be an issue, because strings (base255) are usually not subjected to arithmetic operations such adding, substracting, multiyplying or dividing. The domain of strings and its operations are not expected to be a valid algebraic structure. Therefore, it does not particularly matter in practical terms that the encoding domain is not entirely convex.

You can use the encoding/decoding functions as following:

	$base36x=base36x_encode($text);

	$base255=base36x_decode($base36x);

	text: id=99&title=hello world
	base36x: a9w9q909o9tkmd9gl0b9g9u90a9g9vsb9g8gd290yb9g9q91
	decoded: id=99&title=hello world

This workaround solution does not require installing bcmath or gmp. Enjoy!

