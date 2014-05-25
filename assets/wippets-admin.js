jQuery(function ($) {
	var $document = $(document);

	function WippetsEditor () {};

	$.extend(WippetsEditor.prototype, {
		init: function () {
			this.textarea = $( '#content' );
			this.content_wrap = $( '#wp-content-wrap' );

			if ( this.textarea.length == 0 ) {
				return;
			};

			this.ace_editor_container = $('<div>', {
				'id': 'wippets_code_editor'
			}).insertBefore( this.textarea );

			this.initAce();
			this.initToolbar();
			this.initResize();
			this.initSwitchEditorTabs();

		},
		initAce: function () {
			var self = this;

			this.ace_editor = ace.edit( this.ace_editor_container.get(0) );

			this.ace_editor.setTheme( 'ace/theme/' + wippets_options.ace_theme );
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
			this.content_wrap.find( 'input[name=wippets_language]' ).val( mode );
		},
		getLanguage: function () {
			return this.content_wrap.find( 'input[name=wippets_language]' ).val();
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

			setUserSetting( 'wippets_editor', 'ace' );
		},
		switchEditorToText: function () {
			this.editor_mode = 'text';

			this.content_wrap.removeClass('ace-active').addClass('text-active');

			this.textarea.val( this.ace_editor.getSession().getValue() );

			this.resizeTo( this.editor_height );

			setUserSetting( 'wippets_editor', 'text' );
		},
		getEditorMode: function() {
			var mode;

			if ( typeof this['editor_mode'] !== 'undefined' ) {
				return this.editor_mode;
			}

			mode = getUserSetting( 'wippets_editor');

			if ( mode !== 'ace' && mode !== 'text' ) {
				mode = getUserSetting( 'editor') === 'tinymce' ? 'ace': 'text';
			};

			this.editor_mode = mode;

			return this.editor_mode;
		},

		/* Editor Toolbar */
		initToolbar: function () {
			this.toolbar = {
				element: this.content_wrap.find( '.wippets-toolbar' )
			};

			this.initLanguageSelector();
		},
		initLanguageSelector: function () {
			var self = this;
			var ace_modelist = ace.require( 'ace/ext/modelist' );
			var current_language = this.getLanguage();

			this.toolbar.language_selector = $( '<select>', {
				name: 'wippets_language'
			});

			$( '<option>', {
				text: 'Plain Text',
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
			var $handle = $('#post-status-info');
			var offset = 0, ace_active = true;

			this.resizeTo( getUserSetting( 'ed_size' ) );

			// No resize for touch devices
			if ( 'ontouchstart' in window ) {
				return;
			}

			function dragging( event ) {
				var skip_view_update = ! ace_active;

				self.resizeTo( offset + event.pageY, skip_view_update );

				event.preventDefault();
			}

			function endDrag( event ) {
				var height;

				if ( ace_active ) {
					height = parseInt( self.ace_editor_container.height(), 10 );

					if ( height && height > 50 && height < 5000 ) {
						setUserSetting( 'ed_size', height );
					}

					// Prevent core WordPress script from handling the event
					event.stopImmediatePropagation();
				}

				$document.off( '.wippets-editor-resize' );
			}

			$handle.on( 'mousedown.wp-editor-resize', function( event ) {
				ace_active = self.getEditorMode() == 'ace';
				offset = 0 - event.pageY;

				if ( ace_active ) {
					offset += self.ace_editor_container.height();
				} else {
					offset += self.textarea.outerHeight();
				}

				$document.on( 'mousemove.wippets-editor-resize', dragging )
				$document.on( 'mouseup.wippets-editor-resize mouseleave.wippets-editor-resize', endDrag );

				event.preventDefault();
			}).on( 'mouseup.wp-editor-resize', endDrag );
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

	/* Init Wippets editor */
	(function () {
		var editor;

		if ( $('#content.wippets-enabled-editor-area').length === 0 ) {
			return;
		};

		editor = new WippetsEditor();
		editor.init();
	})();

	/* Settings Page */
	(function () {
		var $form = $('#wippets-settings-form');

		if ( $form.length == 0 ) {
			return;
		};

		init_theme_selector();

		function init_theme_selector() {
			var ace_themelist, selected_theme;
			var ace_theme_selector, theme_options;

			theme_options = $();
			ace_theme_selector = $('#wippets_ace_theme');
			selected_theme = ace_theme_selector.data('default');
			ace_themelist = ace.require( 'ace/ext/themelist' );

			console.log( ace_themelist );

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
		if ( $('#wippets-embed-snippet-button').length === 0 ) {
			return;
		};

		$document.on( 'submit', '#wippets-embed-snippet-form', function (ev) {
			var form = $(this);
			var snippet_id, height;
			var shortcode_args;

			ev.preventDefault();

			snippet_id = parseInt( form.find('#wippet_snippet_id').val() );

			if ( isNaN( snippet_id ) || snippet_id < 1 ) {
				return;
			};

			snippet_title = form.find('#wippet_snippet_id option:selected').html();
			snippet_title = snippet_title.replace(/["'\[\]]/g, '');

			height = parseInt( form.find('#wippet_height').val() );

			if ( isNaN( height ) || height < 0 ) {
				height = 0;
			};

			shortcode_args = [];

			shortcode_args.push( 'id="' + snippet_id + '"' );
			shortcode_args.push( 'title="' + snippet_title + '"' );
			shortcode_args.push( 'height="' + height + '"' );

			if ( form.find('#wippet_show_line_numbers:checked').length === 0 ) {
				shortcode_args.push( 'line_numbers="false"' );
			} else {
				shortcode_args.push( 'line_numbers="true"' );
			}

			addSnippet( '[snippet ' + shortcode_args.join( ' ' ) + ']' );

			tb_remove();
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
});