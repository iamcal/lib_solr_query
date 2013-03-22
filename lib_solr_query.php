<?php

	function solr_query_parse($q, $field=null){

		$parts = array();


		#
		# pre-process
		#

		$pre_map = array(
			"\n"	=> ' ',
			"\t"	=> ' ',
		);

		$q = str_replace(array_keys($pre_map), $pre_map, $q);


		#
		# escape whitespace and commas within quoted blocks
		#

		$q = preg_replace_callback('!"(.*?)"!', solr_query_parse__p1, trim($q));


		#
		# split terms and build the $parts list
		#

		$terms = preg_split('![\s,]+!', $q);

		foreach ($terms as $term){

			if (substr($term, 0, 1) == '"' && substr($term, -1) == '"'){

				$inner = substr($term, 1, -1);
				$term = '"'.solr_query_escape(solr_query_parse__p2($inner)).'"';
			}else{
				$term = solr_query_escape($term);
			}

			$parts[] = $field ? "$field:$term" : $term;
		}

		if (count($parts) > 1){
			return '('.implode(' AND ', $parts).')';
		}

		return $parts[0];
	}

	function solr_query_parse__p1($str){
		$map = solr_query_parse__map();
		return str_replace(array_keys($map), $map, $str[0]);
	}

	function solr_query_parse__p2($str){
		$map = solr_query_parse__map();
		return str_replace($map, array_keys($map), $str);
	}

	function solr_query_parse__map(){
		return array(
			' '	=> '{SOLR-SPACE}',
			','	=> '{SOLR-COMMA}',
		);
	}

	function solr_query_escape($str){

		return preg_replace('!([+\-&|\!(){}\[\]^\"~*?\\\\:])!', '\\\\$1', $str);
	}

