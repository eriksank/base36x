# base36x

Implements a suggestion for base36 based on base64 encoding. You can find the suggestion for these functions [here](http://forums.phpfreaks.com/topic/218241-php-base36-encodingdecoding-for-textdata/).

## Base36

Base 36 is an encoding in which we only use letters (a to z) and digits (0 to 9) to represent data.

It is quite similar to base 64, where we use lowercase letters (a to z), uppercase (A to Z) and digits (0 to 9) to represent the first 62 values, + and / to represent the two last values and = to correct the final translation block.

## What is the problem?

In fact, PHP supports converting to and from base36. However, only from base 10:

	php -r "echo base_convert('555555555',10,36);"
	96rher

	php -r "echo base_convert('96rher',36,10);"
	555555555

Strings are not base 10. They are pretty much base 255. So, what we would need is:

	php -r "echo base_convert('555555555',255,36);"

	php -r "echo base_convert('96rher',36,255);"

The standard PHP library does not implement this functionality. In fact, it would be quite possible to implement it based on existing functions, but the problem is that the standard algorithm needs support for large numbers in order to do that. Therefore, it would require installing the bcmath or gmp php extensions.

## The workaround

In fact, as the suggestion in the forum said, we can also repurpose the base64 encoding/decoding functions to produce base36 output. Now, the result is not a real base36 encoding, because not all combinations of valid base36 characters result in a valid base36 string. From there, the name `base36x` instead of `base36`.

Note: But then again, this should not be an issue, because strings (base255) are usually not subjected to arithmetic operations such adding, substracting, multiyplying or dividing. The domain of strings and its operations are not expected to be a valid algebraic structure. Therefore, it does not particularly matter that the encoding domain is not entirely convex.

You can use the encoding/decoding functions as following:

	$base36x=base36x_encode($text);

	$base255=base36x_decode($base36x);

This solution does not require installing bcmath or gmp. Enjoy!

