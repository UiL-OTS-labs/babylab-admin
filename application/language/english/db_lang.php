<?php

$lang['db_invalid_connection_str'] = 'Could not determine the database settings based on the "connection string" you supplied.';
$lang['db_unable_to_connect'] = 'Could not connect to the database using the current settings you supplied.';
$lang['db_unable_to_select'] = 'Could not select the database: %s';
$lang['db_unable_to_create'] = 'Could not create the database: %s';
$lang['db_invalid_query'] = 'The query you entered is incorrect.';
$lang['db_must_set_table'] = 'You must determine the database table you wish to use.';
$lang['db_must_use_set'] = 'You need to use the "SET" method to update the entry.';
$lang['db_must_use_index'] = 'You have to provide an index that matches the batch updates.';
$lang['db_batch_missing_index'] = 'One or more of the sent rules for batch updating lack the necessary index.';
$lang['db_must_use_where'] = 'Update queries are not allowed unless they contain a "WHERE" clause';
$lang['db_del_must_use_where'] = 'Delete queries are not allowed unless they contain a "WHERE" clause.';
$lang['db_field_param_missing'] = 'To request fields you need to supply the name of the table.';
$lang['db_unsupported_function'] = 'This function is not supported by the database version you are currently using.';
$lang['db_transaction_failure'] = 'Transaction failed: Rollback executed.';
$lang['db_unable_to_drop'] = 'Could not delete the selected database.';
$lang['db_unsuported_feature'] = 'Non-supported function in the used database platform.';
$lang['db_unsuported_compression'] = 'The file compression format you chose is not supported on the server.';
$lang['db_filepath_error'] = 'Unable to write data to the data directory you selected.';
$lang['db_invalid_cache_path'] = 'The cache directory you selected is invalid or not writable.';
$lang['db_table_name_required'] = 'A table name is required for this action.';
$lang['db_column_name_required'] = 'A column name is required for this action.';
$lang['db_column_definition_required'] = 'A column definition is required for this action.';
$lang['db_unable_to_set_charset'] = 'Could not configure the following characterset for the connection: %s';
$lang['db_error_heading'] = 'A database error occured.';
?>