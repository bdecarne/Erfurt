return array(
    'name'              => 'ask-02.rq',
    'group'             => 'RAP Ask Test Cases',
    'result_form'       => 'select',
    'query'             => 'PREFIX  ex: <http://example.com/#>
    PREFIX  rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

    SELECT ?val
    WHERE {
    	ex:foo rdf:value ?val .
    	FILTER regex(?val, "DeFghI", "i")
    }'
);