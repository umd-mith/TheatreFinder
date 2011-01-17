/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license

swatch colors 
(from Elisabeth's theatrefinder.css)
For use in the 'skin' color for the CKeditor window
------------------
ab2023 - red
97c53c - green
ffcf41 - yellow
2c6871 - teal
f69c2d - orange
cde6e9 - light blue
======================== */

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.language = 'en';
	config.uiColor = '#cde6e9';
	config.toolbar = 'Full';
	config.width = "600px";
	config.height = "100px";
	
	config.toolbar = 'tfToolbar';

    config.toolbar_tfToolbar =
    [
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
		['Bold','Italic','Underline','Strike', '-'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['SpecialChar','Link','Unlink', '-', 'Templates'],
       
    ];
	
	// set the toolbar collapse to false (don't want them to collapse it)
	CKEDITOR.config.toolbarCanCollapse = false;
	
	CKEDITOR.config.templates = 'tfTemplates';
	CKEDITOR.addTemplates('tfTemplates', 
		{
			templates:  
			[ 
			  { title: 'Architectural History',
				html:
					'<h4>Previous Theatres on this site</h4>' +
					'<p>Type the text here.</p>' +
					'<h4>Alterations, redecorations, renovations, reconstructions done on the current theatre</h4>' +
					'<p>Type the text here.</p>'
			  },
			  {
			  	title: 'Measurements/Technical Details',
				html: 
					'<h4>Dimensions of current auditorium</h4>' +
					'<p>Type the text here.</p>' +
					'<h4>Dimensions of the current stage</h4>' +
					'<p>Type the text here.</p>'
			  }
			
			]
			
		})

};
/*	From the ckeditor documentation/dev pages
 *  This is actually the default value for the full ckeditor toolbar....
config.toolbar_Full =
[
    ['Source','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
    '/',
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks','-','About']
];
 */
