<?php

/*
	Form Definition

	Tabledefinition

	Datatypes:
	- INTEGER (Forces the input to Int)
	- DOUBLE
	- CURRENCY (Formats the values to currency notation)
	- VARCHAR (no format check, maxlength: 255)
	- TEXT (no format check)
	- DATE (Dateformat, automatic conversion to timestamps)

	Formtype:
	- TEXT (Textfield)
	- TEXTAREA (Textarea)
	- PASSWORD (Password textfield, input is not shown when edited)
	- SELECT (Select option field)
	- RADIO
	- CHECKBOX
	- CHECKBOXARRAY
	- FILE

	VALUE:
	- Wert oder Array

	Hint:
	The ID field of the database table is not part of the datafield definition.
	The ID field must be always auto incement (int or bigint).
	
	Search:
	- searchable = 1 or searchable = 2 include the field in the search
	- searchable = 1: this field will be the title of the search result
	- searchable = 2: this field will be included in the description of the search result


*/

$form["title"] 			= "Web Aliasdomain";
$form["description"] 	= "";
$form["name"] 			= "web_aliasdomain";
$form["action"]			= "web_aliasdomain_edit.php";
$form["db_table"]		= "web_domain";
$form["db_table_idx"]	= "domain_id";
$form["db_history"]		= "yes";
$form["tab_default"]	= "domain";
$form["list_default"]	= "web_aliasdomain_list.php";
$form["auth"]			= 'yes'; // yes / no

$form["auth_preset"]["userid"]  = 0; // 0 = id of the user, > 0 id must match with id of current user
$form["auth_preset"]["groupid"] = 0; // 0 = default groupid of the user, > 0 id must match with groupid of current user
$form["auth_preset"]["perm_user"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_group"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_other"] = ''; //r = read, i = insert, u = update, d = delete

$form["tabs"]['domain'] = array (
	'title' 	=> "Domain",
	'width' 	=> 100,
	'template' 	=> "templates/web_aliasdomain_edit.htm",
	'fields' 	=> array (
	##################################
	# Begin Datatable fields
	##################################
		'server_id' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'SELECT',
			'default'	=> '',
			'datasource'	=> array ( 	'type'	=> 'SQL',
										'querystring' => 'SELECT server_id,server_name FROM server WHERE mirror_server_id = 0 AND {AUTHSQL} ORDER BY server_name',
										'keyfield'=> 'server_id',
										'valuefield'=> 'server_name'
									 ),
			'value'		=> ''
		),
		'domain' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
            'filters'   => array( 0 => array( 'event' => 'SAVE',
                                              'type' => 'IDNTOASCII'),
                                  1 => array( 'event' => 'SHOW',
                                              'type' => 'IDNTOUTF8'),
                                  2 => array( 'event' => 'SAVE',
                                              'type' => 'TOLOWER')
                                ),
            'validators'    => array (  0 => array (    'type'  => 'CUSTOM',
                                                        'class' => 'validate_domain',
                                                        'function' => 'alias_domain',
                                                        'errmsg'=> 'domain_error_regex'),
                                    ),
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255',
			'searchable' => 1
		),
		'type' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'SELECT',
			'default'	=> 'y',
			'value'		=> array('vhost' => 'Site', 'alias' => 'Alias', 'subdomain' => 'Subdomain')
		),
		'parent_domain_id' => array (
			'datatype'	=> 'INTEGER',
			'formtype'	=> 'SELECT',
			'default'	=> '',
			'datasource'	=> array ( 	'type'	=> 'SQL',
										'querystring' => "SELECT domain_id,domain FROM web_domain WHERE type = 'vhost' AND {AUTHSQL} ORDER BY domain",
										'keyfield'=> 'domain_id',
										'valuefield'=> 'domain'
									 ),
			'value'		=> ''
		),
		'redirect_type' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'SELECT',
			'default'	=> 'y',
			'value'		=> array('' => 'no_redirect_txt', 'no' => 'no_flag_txt', 'R' => 'R', 'L' => 'L', 'R,L' => 'R,L', 'R=301,L' => 'R=301,L', 'last' => 'last', 'break' => 'break', 'redirect' => 'redirect', 'permanent' => 'permanent', 'proxy' => 'proxy')
		),
		'redirect_path' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'TEXT',
			'validators'	=> array ( 	0 => array (	'type'	=> 'REGEX',
														'regex' => '@^(([\.]{0})|((ftp|https?)://([-\w\.]+)+(:\d+)?(/([\w/_\.\-\,\+\?\~!:%]*(\?\S+)?)?)?)|(\[scheme\]://([-\w\.]+)+(:\d+)?(/([\w/_\.\-\,\+\?\~!:%]*(\?\S+)?)?)?)|(/(?!.*\.\.)[\w/_\.\-]{1,255}/))$@',
														'errmsg'=> 'redirect_error_regex'),
									),
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
		'subdomain' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'SELECT',
			'default'	=> 'www',
            'validators'    => array (  0 => array (    'type'  => 'CUSTOM',
                                                        'class' => 'validate_domain',
                                                        'function' => 'web_domain_autosub',
                                                        'errmsg'=> 'domain_error_autosub'),
                                    ),
			'value'		=> array('none' => 'none_txt', 'www' => 'www.', '*' => '*.')
		),
		'seo_redirect' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'SELECT',
			'default'	=> '',
			'value'		=> array('' => 'no_redirect_txt', 'non_www_to_www' => 'domain.tld => www.domain.tld', 'www_to_non_www' => 'www.domain.tld => domain.tld', '*_domain_tld_to_domain_tld' => '*.doman.tld => domain.tld', '*_domain_tld_to_www_domain_tld' => '*.domain.tld => www.domain.tld', '*_to_domain_tld' => '* => domain.tld', '*_to_www_domain_tld' => '* => www.domain.tld')
		),
		'active' => array (
			'datatype'	=> 'VARCHAR',
			'formtype'	=> 'CHECKBOX',
			'default'	=> 'y',
			'value'		=> array(0 => 'n',1 => 'y')
		),
	##################################
	# ENDE Datatable fields
	##################################
	)
);

if($_SESSION["s"]["user"]["typ"] == 'admin') {

$form["tabs"]['advanced'] = array (
	'title' 	=> "Options",
	'width' 	=> 100,
	'template' 	=> "templates/web_aliasdomain_advanced.htm",
	'readonly'	=> false,
	'fields' 	=> array (
	##################################
	# Begin Datatable fields
	##################################
		'proxy_directives' => array (
			'datatype'	=> 'TEXT',
			'formtype'	=> 'TEXT',
			'default'	=> '',
			'value'		=> '',
			'width'		=> '30',
			'maxlength'	=> '255'
		),
	##################################
	# ENDE Datatable fields
	##################################
	)
);

}


?>
