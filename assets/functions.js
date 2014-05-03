jQuery(function ($) {
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

			this.ace_editor.setValue( this.textarea.get(0).value, -1 );

			this.setLanguage( this.getLanguage() );

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
			var $document = $(document);
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

			function endDrag() {
				var height;

				if ( ace_active ) {
					height = parseInt( self.ace_editor_container.height(), 10 );

					if ( height && height > 50 && height < 5000 ) {
						setUserSetting( 'ed_size', height );
					}
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

	var editor = new WippetsEditor();
	editor.init();
});