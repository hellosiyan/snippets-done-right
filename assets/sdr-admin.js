jQuery(function ($) {
	var $document = $(document);

	function SDREditor () {};
	function SDREmbedPopup () {};

	$.extend(SDREditor.prototype, {
		init: function () {
			this.textarea = $( '#content' );
			this.content_wrap = $( '#wp-content-wrap' );

			if ( this.textarea.length == 0 ) {
				return;
			};

			this.ace_editor_container = $('<div>', {
				'id': 'sdr_code_editor'
			}).insertBefore( this.textarea );

			this.initAce();
			this.initToolbar();
			this.initResize();
			this.initSwitchEditorTabs();

		},
		initAce: function () {
			var self = this;

			this.ace_editor = ace.edit( this.ace_editor_container.get(0) );

			this.ace_editor.setTheme( 'ace/theme/' + sdr_options.ace_theme );
			this.ace_editor.setHighlightActiveLine( false );
			this.ace_editor.setShowPrintMargin( false );
			this.setLanguage( this.getLanguage() );

			this.ace_editor.setValue( this.textarea.get(0).value, -1 );

			this.ace_editor.getSession().on( 'change', function () {
				self.textarea.val( self.ace_editor.getSession().getValue() );
			});
		},
		setLanguage: function ( mode ) {
			if ( ! mode ) {
				return;
			};

			this.ace_editor.getSession().setMode('ace/mode/' + mode);
			this.content_wrap.find( 'input[name=sdr_language]' ).val( mode );
		},
		getLanguage: function () {
			return this.content_wrap.find( 'input[name=sdr_language]' ).val();
		},

		/* Editor Tabs */
		initSwitchEditorTabs: function () {
			var self = this;

			this.content_wrap.on('click', '.wp-switch-editor', function() {
				var $this = $(this);

				if ( $this.is('.switch-text') ) {
					self.switchEditorToText();
				} else if ( $this.is('.switch-ace') ) {
					self.switchEditorToAce();
				}
			});

			if ( this.getEditorMode() === 'ace' ) {
				this.switchEditorToAce();
			} else {
				this.switchEditorToText();
			};
		},
		switchEditorToAce: function () {
			this.editor_mode = 'ace';

			this.content_wrap.removeClass('text-active').addClass('ace-active');

			this.ace_editor.setValue( this.textarea.get(0).value, -1 );

			this.resizeTo( this.editor_height );

			setUserSetting( 'sdr_editor', 'ace' );
		},
		switchEditorToText: function () {
			this.editor_mode = 'text';

			this.content_wrap.removeClass('ace-active').addClass('text-active');

			this.textarea.val( this.ace_editor.getSession().getValue() );

			this.resizeTo( this.editor_height );

			setUserSetting( 'sdr_editor', 'text' );
		},
		getEditorMode: function() {
			var mode;

			if ( typeof this['editor_mode'] !== 'undefined' ) {
				return this.editor_mode;
			}

			mode = getUserSetting( 'sdr_editor');

			if ( mode !== 'ace' && mode !== 'text' ) {
				mode = getUserSetting( 'editor') === 'html' ? 'text': 'ace';
			};

			this.editor_mode = mode;

			return this.editor_mode;
		},

		/* Editor Toolbar */
		initToolbar: function () {
			this.toolbar = {
				element: this.content_wrap.find( '.sdr-toolbar' )
			};

			this.initLanguageSelector();
		},
		initLanguageSelector: function () {
			var self = this;
			var ace_modelist = ace.require( 'ace/ext/modelist' );
			var current_language = this.getLanguage();

			this.toolbar.language_selector = $( '<select>', {
				name: 'sdr_language'
			});

			$( '<option>', {
				text: sdr_strings.plain_text,
				value: 'text'
			}).appendTo( this.toolbar.language_selector );

			for (var i = 0; i < ace_modelist.modes.length; i++) {
				if ( ace_modelist.modes[i].name === 'text' ) {
					continue;
				};

				$('<option>', {
					text: ace_modelist.modes[i].caption,
					value: ace_modelist.modes[i].name
				}).appendTo( this.toolbar.language_selector );
			};

			this.toolbar.language_selector.val( current_language );

			this.toolbar.language_selector.on( 'change', function() {
				self.setLanguage( this.value );
			});

			this.toolbar.language_selector.appendTo( this.toolbar.element );
		},

		/* Resize Handler */
		initResize: function() {
			var self = this;

			this.ace_editor.on( 'change', function () {
				var lines_count = self.ace_editor.session.getLength();
				var line_height = self.ace_editor.renderer.layerConfig.lineHeight;

				self.resizeTo( (lines_count + 1) * line_height );
			});
		},
		resizeTo: function( height, skip_view_update ) {
			height = isNaN( height ) ? 0: parseInt( height );
			height = Math.max( 50, height );
			
			this.editor_height = height;

			if ( skip_view_update ) {
				return;
			};

			if ( this.getEditorMode() == 'ace' ) {
				this.ace_editor_container.outerHeight( height );
				this.ace_editor.resize();
			} else {
				this.textarea.outerHeight( height );
			}
			
		}
	});

	$.extend(SDREmbedPopup.prototype, {
		init: function() {
			var self = this;

			this.backdrop = $('#sdr-embed-dialog-backdrop');
			this.wrap = $('#sdr-embed-dialog-wrap');
			this.close_button = $('#sdr-embed-dialog-close');
			this.cancel_button = $('#sdr-embed-dialog-cancel');

			this.close_button
				.add(this.backdrop)
				.add(this.cancel_button)
				.on('click', function(e) {
					e.preventDefault();
					self.close();
				});
		},
		open: function() {
			this.backdrop.show();
			this.wrap.show();
		},
		close: function() {
			this.backdrop.hide();
			this.wrap.hide();
		}
	});
	

	/* Init SDR editor */
	(function () {
		var editor;

		if ( $('#content.sdr-enabled-editor-area').length === 0 ) {
			return;
		};

		editor = new SDREditor();
		editor.init();
	})();

	/* Settings Page */
	(function () {
		var $form = $('#sdr-settings-form');

		if ( $form.length == 0 ) {
			return;
		};

		init_theme_selector();

		function init_theme_selector() {
			var ace_themelist, selected_theme;
			var ace_theme_selector, theme_options;

			theme_options = $();
			ace_theme_selector = $('#sdr_ace_theme');
			selected_theme = ace_theme_selector.data('default');
			ace_themelist = ace.require( 'ace/ext/themelist' );

			for (var i = 0; i < ace_themelist.themes.length; i++) {
				theme_options = theme_options.add( $('<option>', {
					text: ace_themelist.themes[i].caption,
					value: ace_themelist.themes[i].name
				}));
			};

			theme_options.appendTo( ace_theme_selector );
			ace_theme_selector.val( selected_theme );
		}
	})();

	/* Embed Snippet screen */
	(function () {
		var button_open_dialog = $('#sdr-embed-snippet-button');
		var popup;

		if ( button_open_dialog.length === 0 ) {
			return;
		};

		popup = new SDREmbedPopup();

		popup.init();

		button_open_dialog.on('click', function (e) {
			e.preventDefault();
			popup.open();
		});


		$document.on( 'submit', '#sdr-embed-snippet-form', function (ev) {
			var form = $(this);
			var snippet_id, snippet_title, height;
			var shortcode_args;

			ev.preventDefault();

			snippet_id = parseInt( form.find('#sdr_snippet_id').val() );

			if ( isNaN( snippet_id ) || snippet_id < 1 ) {
				return;
			};

			snippet_title = form.find('#sdr_snippet_id option:selected').html();
			snippet_title = snippet_title.replace(/["'\[\]]/g, '');

			height = parseInt( form.find('#sdr_height').val() );

			if ( isNaN( height ) || height < 0 ) {
				height = 0;
			};

			shortcode_args = [];

			shortcode_args.push( 'id="' + snippet_id + '"' );
			shortcode_args.push( 'title="' + snippet_title + '"' );
			shortcode_args.push( 'height="' + height + '"' );

			if ( form.find('#sdr_show_line_numbers:checked').length === 0 ) {
				shortcode_args.push( 'line_numbers="false"' );
			} else {
				shortcode_args.push( 'line_numbers="true"' );
			}

			addSnippet( '[snippet ' + shortcode_args.join( ' ' ) + ']' );

			popup.close();
		});

		function addSnippet( shortcode ) {
			var editor, shortcode;
			var has_tinymce = typeof tinymce !== 'undefined';
			var has_quicktags = typeof QTags !== 'undefined';


			if ( send_to_editor ) {
				return send_to_editor( shortcode );
			}

			if ( ! wpActiveEditor ) {
				if ( ! has_quicktags ) {
					return false;
				}

				if ( has_tinymce && tinymce.activeEditor ) {
					editor = tinymce.activeEditor;
					wpActiveEditor = window.wpActiveEditor = editor.id;
				}
			} else if ( has_tinymce ) {
				editor = tinymce.get( wpActiveEditor );
			}

			if ( editor && ! editor.isHidden() ) {
				editor.execCommand( 'mceInsertContent', false, shortcode );
			} else if ( has_quicktags ) {
				QTags.insertContent( shortcode );
			} else {
				document.getElementById( wpActiveEditor ).value += shortcode;
			}
		}
	})();

	/* Snippets listing screen */
	(function() {
		var $rows = $('.wp-list-table.posts tr.type-sdr_snippet');
		var ace_modelist;
		var placeholder;

		if ( $rows.length == 0 ) {
			return;
		};

		ace_modelist = ace.require( 'ace/ext/modelist' );

		for (var i = 0; i < ace_modelist.modes.length; i++) {
			placeholder = $rows.find( 'span[data-language="' + ace_modelist.modes[i].name + '"]' );
			placeholder.text( ace_modelist.modes[i].caption );
		};

	})();
});