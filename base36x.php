<?php

/*	
	==================================================================

	base36x
	Written By Erik Poupaert, Phnom Penh, Cambodia, Jan 2016
	Implementing the idea in: 

	http://forums.phpfreaks.com/topic/218241-php-base36-encodingdecoding-for-textdata/

	License LGPL

	==================================================================
*/


/* char_between: convenience macro */

function char_between($char,$startCharRange,$endCharRange) {
	return $char>=$startCharRange && $char<=$endCharRange; 
}

/* base36x_encode_char: encodes just one character */

function base36x_encode_char($char) {
	if(char_between($char,'a','z')) return $char;
	if(char_between($char,'0','8')) return $char;
	switch($char) {
		case '9': return '90';
		case '=': return '91'; 
		case '/': return '92'; 
		case '+': return '93';
		default: return '9'.strtolower($char);
	}
}

/* base36x_encode: encodes a string */

function base36x_encode($string) {
	$base64=base64_encode($string);
	$result='';
	for($i=0;$i<strlen($base64);$i++) {
		$result.=base36x_encode_char($base64[$i]);
	}
	return $result;
}

/* base36x_decode_char: decodes 1 character, or 1 character and 1 lookahead */

function base36x_decode_char($char,$lookahead) {
	if(char_between($char,'a','z')) return $char;
	if(char_between($char,'0','8')) return $char;
	if($char=='9') {
		switch($lookahead) {
			case '0': return '9';
			case '1': return '='; 
			case '2': return '/'; 
			case '3': return '+';
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9': return false; // 94-99 are not valid base36x sequences
			default: return strtoupper($lookahead);
		}
	}
	return false;
}

/* base36x_decode: decodes a string */

function base36x_decode($string) {
	if(!preg_match('|[a-z0-9]*|',$string)) {
		//not a valid base36x string
		return false;
	}
	$size=strlen($string);
	$result='';
	for($i=0;$i<$size-1;$i++) {
		$transl=base36x_decode_char($string[$i],$string[$i+1]);
		if($transl===false) {
			return false; //sequence invalid
		}
		$result.=$transl;
		if($string[$i]=='9') $i++; //lookahead consumed
	}
	return base64_decode($result);
}

//--------------------------------------
// test
//--------------------------------------

$text="id=99&title=hello world";
echo "text: $text\n";
$base36x=base36x_encode($text);
echo "base36x: $base36x\n";
$base255=base36x_decode($base36x);
echo "decoded: $base255\n";

