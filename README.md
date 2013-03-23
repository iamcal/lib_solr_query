# php-query-parse - Turn human queries into SOLR queries

If you're taking search queries from users, sending them directly to SOLR wont return what
you mean, most of the time. Even ignore input sanitizing, dealing with nesting and booleans
correctly is a PITA. This library tries to hide all the pain.

Some assumptions are made about what you mean to do in ambiguous cases - `"foo bar"` is taken 
to mean "match documents with both `foo` and `bar`, which is not how Lucene queries work (they
assume an 'or' operation for a bare list of terms). There are currently no configurable options,
so changing this behavior involves modifiying the code.

For examples of the produced queries, take a look at the tests.


## Usage

    include('lib_solr_query.php');

    $query = solr_query_parse($_GET['q']);

The parser supports multi-level nesting, booleans (AND, OR, NOT), phrases and inclusion/exclusion
with +/-.


## Background reading

* http://lucene.apache.org/core/4_0_0/queryparser/org/apache/lucene/queryparser/classic/package-summary.html
* http://robotlibrarian.billdueber.com/solr-and-boolean-operators/
