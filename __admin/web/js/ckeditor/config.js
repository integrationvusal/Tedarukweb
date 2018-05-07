CKEDITOR.on( 'dialogDefinition', function( ev ){
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;

	if ( dialogName == 'link' )
		dialogDefinition.removeContents( 'upload' );

	if ( dialogName == 'image' )
		dialogDefinition.removeContents( 'Upload' );

	if ( dialogName == 'flash' )
		dialogDefinition.removeContents( 'Upload' );

});

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.tabSpaces = 4;
	//config.pasteFromWordRemoveFontStyles = false;
	config.pasteFromWordRemoveStyles = false;
	config.pasteFromWordRemoveFontStyles = false;
	config.allowedContent = true;
	//config.pasteFromWordPromptCleanup
};
