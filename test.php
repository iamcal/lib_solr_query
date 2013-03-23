<?php
	include('testmore.php');
	include('lib_solr_query.php');


	$map = array(

		# simple queries
		'foo'			=> 'foo',
		'foo bar'		=> '(foo AND bar)',

		# escaped characters
		'"woo+yay"'		=> '"woo\\+yay"',
		'foo * bar'		=> '(foo AND \\* AND bar)',

		# + and - operators
		'+foo +bar'		=> '(+foo +bar)',
		'+foo bar'		=> '(+foo bar)',
		'+foo bar baz'		=> '(+foo (bar AND baz))',
		'foo -bar'		=> '(-bar foo)', # caused by term re-ordering and grouping
		'-foo -bar'		=> '(-foo -bar)',

		# and & or booleans
		'foo AND bar'		=> '(foo AND bar)',
		'foo OR bar'		=> '(foo OR bar)',
		'foo AND (bar OR baz)'	=> '(foo AND (bar OR baz))',
		'foo bar OR baz'	=> '(foo AND (bar OR baz))',
		'foo AND bar OR baz'	=> '((foo AND bar) OR baz)',
		'foo AND NOT bar'	=> '(foo AND -bar)',

		# not boolean
		'foo NOT bar'		=> '(-bar foo)',
		'foo OR NOT bar'	=> '(foo OR -bar)',
		'NOT foo AND NOT bar'	=> '(-foo AND -bar)',
		'NOT foo AND bar'	=> '(-foo AND bar)',

		# incorrect booleans
		'foo AND'		=> '(foo AND "AND")',
		'foo AND bar AND'	=> '((foo AND bar) AND "AND")',
		'foo AND bar or'	=> '((foo AND bar) AND "or")',
		'foo not'		=> '(foo AND "not")',

		# phrases
		'"foo bar"'		=> '"foo bar"',
		'"foo bar" baz'		=> '("foo bar" AND baz)',
		'"foo bar" and baz'	=> '("foo bar" AND baz)',
		'"foo bar" or baz'	=> '("foo bar" OR baz)',

		# implicit phrases
		'foo and bar baz'	=> 'foo AND "bar baz"',
		'foo or bar baz'	=> 'foo OR "bar baz"',
		'foo bar and baz'	=> '"foo bar" AND baz',
		'foo bar or baz'	=> '"foo bar" OR baz',
	);


	plan(count($map));

	foreach ($map as $k => $v){

		is(solr_query_parse($k), $v, "Parsing $k");
	}
