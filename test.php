<?php
	include('testmore.php');
	include('query_parse.php');


	$map = array(

		'foo'			=> 'foo',
		'foo bar'		=> 'foo AND bar',
		'+foo +bar'		=> 'foo AND bar',
		'+foo bar'		=> 'foo OR (foo AND bar)',
		'+foo bar baz'		=> 'foo OR (foo AND bar) OR (foo AND baz) OR (foo AND bar AND baz)',
		'foo -bar'		=> 'foo NOT bar',
		'-foo -bar'		=> 'foo AND bar',
		'foo AND bar'		=> 'foo AND bar',
		'foo OR bar'		=> 'foo OR bar',
		'foo AND (bar OR baz)'	=> 'foo AND (bar OR baz)',
		#'foo bar OR baz'	=> 'foo AND (bar OR baz)',
		'foo AND bar OR baz'	=> 'foo AND (bar OR baz)',
		'foo AND NOT bar'	=> 'foo NOT bar',

		'foo NOT bar'		=> 'foo NOT bar',
		'foo OR NOT bar'	=> 'foo OR "not bar"',
		'NOT foo AND NOT bar'	=> '"not foo" NOT bar',
		'NOT foo AND bar'	=> '"not foo" AND bar',
		'foo AND'		=> 'foo AND and',
		'foo AND bar AND'	=> 'foo AND "bar and"',
		'foo AND bar or'	=> 'foo AND "bar or"',

		'"foo bar"'		=> '"foo bar"',
		'"foo bar" baz'		=> '"foo bar" AND baz',
		'"foo bar" and baz'	=> '"foo bar" AND baz',
		'"foo bar" or baz'	=> '"foo bar" OR baz',

		'foo and bar baz'	=> 'foo AND "bar baz"',
		'foo or bar baz'	=> 'foo OR "bar baz"',
		'foo bar and baz'	=> '"foo bar" AND baz',
		'foo bar or baz'	=> '"foo bar" OR baz',
	);


	plan(count($map));

	foreach ($map as $k => $v){

		is(query_parse($k), $v, "Parsing $k");
	}
